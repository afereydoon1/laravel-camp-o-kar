<?php

namespace App\Http\Controllers;

use App\Models\Download;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadFiles extends Controller
{
    public function download(File $file)
    {
        return Storage::download($file->file_src);
    }


    public function downloadZip(Download $download)
    {
        $this->authorize('view', $download);

        return response()->streamDownload(function () use ($download) {
            echo $download->getFileContent();
        }, $download->zip_name);
    }
}
