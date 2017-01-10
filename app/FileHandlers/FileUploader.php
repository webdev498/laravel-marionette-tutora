<?php namespace App\FileHandlers;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

abstract class FileUploader
{

    protected $filename;
    protected $path;
    protected $storage_hash;

    /**
     * Return file type-specific folder ('img', etc.)
     *
     * @return mixed
     */
    abstract protected function getFolder();

    public function getDirectory()
    {
        return storage_path(). $this->getFolder();
    }

    public function upload($file, Array $options = [])
    {
        $this->options = $options;

        $this->saveOrigin($file);

        return $this->path;
    }

    /**
     * @param $file
     *
     * @return File A File object representing the new file
     */
    public function saveOrigin($file)
    {
        $root = $this->getDirectory() . '/origin/';

        $this->generateHash($root);

        $file = $this->saveFile($root.$this->path, $file);

        return $file;
    }

    /**
     * Remove origin file
     *
     * @return bool true on success or false on failure.
     */
    protected function removeOrigin()
    {
        $root = $this->getDirectory() . '/origin/';

        $path = $root.$this->path.'/'.$this->filename;

        return $this->removeFile($path);
    }

    /**
     * Remove origin file
     *
     * @param string $subfolder
     *
     * @return bool true on success or false on failure.
     */
    protected function remove($subfolder)
    {
        $root = $this->getDirectory() . '/'.$subfolder.'/';

        $path = $root.$this->path.'/'.$this->filename;

        return $this->removeFile($path);
    }

    /**
     * @param $path
     * @return bool|null
     */
    private function removeFile($path)
    {
        return is_file($path) ? unlink($path) : false;
    }

    private function generateHash($root)
    {
        do {
            $this->storage_hash = md5(time().rand());

            $path = $this->generatePathFromHash();
            $filename = $this->generateFilenameFromHash();

        } while(file_exists($root.$path.'/'.$filename));

        $this->filename     = $filename;
        $this->path         = $path;
    }

    /**
     * @param string $hash
     *
     * @return string
     */
    protected function generatePathFromHash()
    {
        $hash = $this->storage_hash;

        return substr($hash, 0, 2).'/'.substr($hash, 2, 2).'/'.substr($hash, 4, 2);
    }

    /**
     * @param string $hash
     *
     * @return string
     */
    protected function generateFilenameFromHash()
    {
        $hash = $this->storage_hash;

        return substr($hash, 6);
    }

    /**
     * @param $path
     * @param UploadedFile $file
     *
     * @return File A File object representing the new file
     */
    private function saveFile($path, $file)
    {
        return $file->move($path, $this->filename);
    }

    /**
     * @param $subfolder
     *
     * @return string
     */
    protected function getFullFilePath($subfolder)
    {
        $path = $this->getDirectory().'/'.$subfolder.'/'.$this->path;

        if(!is_dir($path)){
            mkdir($path, 0777, true);
        }

        return $path.'/'.$this->filename;
    }

}
