<?php


namespace App\Traits;


use App\Http\Controllers\Admin\FileController;
use App\Http\Requests\FileRequest;
use App\Models\File;

trait MakeFileModel
{
    protected $file;

    /**
     * @param FileRequest $request
     * @param string|null $file_name
     * @param string|null $image_name
     * @param File|null $file
     * @return File
     */
    protected function pruneData(
        FileRequest $request,
        string $file_name = null,
        string $image_name = null,
        File $file = null): File
    {
        $data = $request->except('selectedTags', 'file', 'image');

        if ($file_name) {
            $data['file'] = $file_name;
        }
        if ($image_name) {
            $data['image'] = $image_name;
        }

        if ($file !== null) {
            tap($file)->update($data);
        } else {
            $file = File::create($data);
        }
        return $file;
    }

    /**
     * @param FileRequest $request
     * @param File|null $file
     * @return mixed
     */
    protected function createOrUpdateFile(FileRequest $request, File $file = null)
    {
        return \DB::transaction(function () use ($request, $file) {
            try {
                if ($file === null) {
                    $file_name = $this->makeFile($request);
                    $image_name = $this->makePublicImage($request);
                    $file = $this->pruneData($request, $file_name, $image_name);
                } else {
                    $file_name = $this->makeFile($request, $file);
                    $image_name = $this->makePublicImage($request, $file);
                    $file = $this->pruneData($request, $file_name, $image_name, $file);
                }

                $file->syncCategories($request->selectedTags);

                return 200;
            } catch (\Exception $exception) {
                \DB::rollback();

                $this->file->remove('files/' . $file_name);
                return 500;
            }
        });
    }

    /**
     * @param FileRequest $request
     * @param File $file
     * @return string|null
     */
    protected function makeFile(FileRequest $request, File $file = null)
    {
        if ($request->hasFile('file')) {
            if ($file !== null) {
                $this->file->remove($file->file_src);
            }

            return $this->file->storeStorage($request->file('file'));
        }
        return null;
    }

    /**
     * @param FileRequest $request
     * @param File $file
     * @return string|null
     */
    protected function makePublicImage(FileRequest $request, File $file = null)
    {
        if ($request->hasFile('image')) {
            if ($file !== null) {
                $this->file->removeFromPublic($file->image_src);
            }

            return $this->file->storePublicImage($request->file('image'));
        }
        return null;
    }
}
