<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Download extends Model
{
    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'link';
    }

    public function scopeGetLastDownload(Builder $builder, $file_id, $type = 'public')
    {
        return $builder->whereUserId(\request()->user()->id)
            ->whereFileId($file_id)
            ->where('expire_at', '>', now())
            ->where('type', $type)
            ->latest();
    }

    public function fileExists(): bool
    {
        return $this->type == 'public'
            ? File::exists(public_path('zip/' . $this->zip_name))
            : Storage::disk('ftp')->exists($this->zip_name);
    }

    public function getFileContent()
    {
        return $this->type == 'public'
            ? File::get(public_path('zip/' . $this->zip_name))
            : Storage::disk('ftp')->get($this->zip_name);
    }
}
