<?php


namespace App\Service;


use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class FileService
{
    /**
     * @param  \Illuminate\Http\UploadedFile $file
     * @return string
     */
    public function storeStorage($file): string
    {
        $file_name = $this->setFileName($file);

        $file->storeAs(
            'files', $file_name
        );

        return $file_name;
    }
    /**
     * @param \Illuminate\Http\UploadedFile $image
     * @return string
     */
    public function storePublicImage($image): string
    {
        $image_name = $this->setFileName($image);

        $image->move(
            public_path('images/'), $image_name
        );
        $image_src = public_path('images/') . $image_name;
        Image::make($image_src)
                ->fit(400)
                ->save($image_src);

        return $image_name;
    }

    public function remove(string $src): bool
    {
        if (Storage::exists($src)) {
            Storage::delete($src);
            return true;
        }
        return false;
    }

    public function removeFromPublic(string $src): bool
    {
        if (File::exists($src)) {
            File::delete($src);
            return true;
        }
        return false;
    }

    /**
     * @var \Illuminate\Http\UploadedFile $file
     * @return string
     */
    protected function setFileName($file): string
    {
        return Str::random(20) . '--' . $file->getClientOriginalName();
    }
}
