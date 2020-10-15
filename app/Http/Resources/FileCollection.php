<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

class FileCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($file) {
                return $file->makeVisible('file');
            })->filter(function ($file) {
                return Storage::exists($file->file_src);
            })
        ];
    }

    public function with($request)
    {
        return [
            'meta' => [
                'queries' => $request->getQueryString()
            ]
        ];
    }
}
