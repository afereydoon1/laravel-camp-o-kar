<?php


namespace App\Service;


use App\Models\Download;
use App\Models\File;
use Illuminate\Support\Facades\File as FileFacade;
use Illuminate\Support\Str;

class ZipService
{

    /**
     * @var File
     */
    protected $file;
    /**
     * @var string
     */
    protected $file_name;
    /**
     * @var string
     */
    protected $zip_name;
    /**
     * @var string
     */
    protected $password;
    /**
     * @var \ZipArchive
     */
    protected $zip;
    /**
     * @var string
     */
    protected $link;

    public function __construct()
    {
        if (! FileFacade::isDirectory(public_path('zip'))) {
            FileFacade::makeDirectory(public_path('zip'));
        }
    }

    public function handle()
    {
        return $this->setProps()
                ->generateZip()
                ->getDownload();
    }

    public function getDownloadModel(File $file)
    {
        $this->file = $file;
        return $this->handle();
    }

    protected function setProps()
    {
        $this->file_name = Str::afterLast($this->file->file, '--');
        $this->zip_name = Str::random(10) . '.zip';
        $this->password = Str::random(5);
        $this->link = Str::random(10);

        $this->zip = new \ZipArchive();

        return $this;
    }

    protected function generateZip()
    {

        $this->zip->open("zip/{$this->zip_name}", \ZipArchive::CREATE);
        $this->zip->addFile($this->file->storage_path, $this->file_name);
        $this->zip->setEncryptionName($this->file_name, \ZipArchive::EM_AES_256, $this->password);

        $this->zip->close();

        return $this;
    }

    public function getDownload()
    {
        return Download::create([
            'link' => $this->link,
            'password' => $this->password,
            'zip_name' => $this->zip_name,
            'user_id' => request()->user()->id,
            'file_id' => $this->file->id,
            'expire_at' => now()->addDay()
        ]);
    }


}
