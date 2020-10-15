<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\MyFilesCollection;
use App\Models\Download;
use App\Models\File;
use App\Service\ZipService;
use \Illuminate\Support\Facades\File as FileFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MyFilesController extends Controller
{
    public function index(Request $request)
    {
        return MyFilesCollection::make($request->user()->files()->with('categories')->paginate(10));
    }

    public function addToMyFiles(Request $request)
    {
        $request->user()->files()->attach($request->file_id);

        return response(['ok'], 200);
    }

    public function generateZip(File $file, ZipService $zipService)
    {
        $this->authorize('view', $file);
        $download = Download::getLastDownload($file->id)->first();
        if ($download && $download->fileExists())
        {
            return $download;
        }

        return $zipService->getDownloadModel($file);
    }

    public function ftpGenerateZip(File $file, ZipService $zipService)
    {
        $this->authorize('view', $file);
        $download = Download::getLastDownload($file->id, 'ftp')->first();
        if ($download && $download->fileExists())
        {
            return $download;
        }

        $download = $zipService->getDownloadModel($file);

        $this->manageFtpZip($download);

        return $download;
    }

    protected function manageFtpZip($download)
    {
        $download->update(['type' => 'ftp']);

        $file_src = public_path("zip/{$download->zip_name}");
        $file_content = FileFacade::get($file_src);

        Storage::disk('ftp')->put($download->zip_name, $file_content);
        FileFacade::delete($file_src);
    }
}
