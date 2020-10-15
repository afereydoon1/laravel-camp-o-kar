<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileRequest;
use App\Http\Resources\FileCollection;
use App\Models\File;
use App\Service\FileService;
use App\Traits\MakeFileModel;
use Illuminate\Http\Request;

class FileController extends Controller
{

    use MakeFileModel;


    public function __construct()
    {
        $this->file = new FileService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return FileCollection
     */
    public function index()
    {
        return new FileCollection(
            File::with('categories', 'membership')
                ->sortByUrl()
                ->searchByUrl()
                ->paginate(10)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param FileRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(FileRequest $request)
    {
        $status = $this->createOrUpdateFile($request);
        return response(['ok'], $status);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\File  $file
     * @return File
     */
    public function show(File $file)
    {
        return $file;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function update(FileRequest $request, File $file)
    {
        $status = $this->createOrUpdateFile($request, $file);
        return response(['ok'], $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
        $this->file->remove($file->file_src);
        $this->file->removeFromPublic($file->image_src);

        $file->delete();

        return response(['ok'], 200);
    }

}
