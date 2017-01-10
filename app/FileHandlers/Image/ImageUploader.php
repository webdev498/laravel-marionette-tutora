<?php namespace App\FileHandlers\Image;

use Image;
use App\Image as ImageEntity;
use App\FileHandlers\FileUploader;
use App\FileHandlers\Image\Filters\SmallImageFilter;
use App\FileHandlers\Image\Filters\LargeImageFilter;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploader extends FileUploader
{

    const FILTER_LARGE = 'large';
    const FILTER_SMALL = 'small';

    protected $options = [
        'ext'  => 'jpg',
    ];

    /**
     * @param ImageEntity $image
     */
    public function setImage(ImageEntity $image)
    {
        $this->storage_hash = $image->storage_hash;

        $this->filename = $this->generateFilenameFromHash();
        $this->path = $this->generatePathFromHash();
    }

    /**
     * @param ImageEntity $image
     */
    public function removeImage(ImageEntity $image)
    {
        $this->setImage($image);

        $this->removeOrigin();

        $filters = $this->getFilterKeys();
        foreach($filters as $filter) {
            $this->remove($filter);
        }
    }

    protected function getFilters()
    {
        return [
            self::FILTER_LARGE => new LargeImageFilter(),
            self::FILTER_SMALL => new SmallImageFilter(),
        ];
    }

    protected function getFilterKeys()
    {
        return [
            self::FILTER_LARGE,
            self::FILTER_SMALL,
        ];
    }

    protected function getFolder()
    {
        return '/images';
    }

    public function getFilename($key)
    {
        $filenames = $this->getFilenames();
        return array_get($filenames, $key);
    }

    public function getFilenames()
    {
        $template = $this->getFolder().'/'.":filter/".$this->path.'/'.$this->filename.".:ext";

        return [
            'origin' => templ($template, array_extend([
                'filter' => 'origin',
            ], $this->options)),

            self::FILTER_LARGE => templ($template, array_extend([
                'filter' => self::FILTER_LARGE,
            ], $this->options)),

            self::FILTER_SMALL => templ($template, array_extend([
                'filter' => self::FILTER_SMALL,
            ], $this->options)),
        ];
    }

    /**
     * @param UploadedFile $file
     * @param array $options
     * @return array
     */
    public function upload($file, Array $options = [])
    {
        $originalName = $file->getClientOriginalName();
        $type         = $file->getClientMimeType();
        $size         = $file->getMaxFilesize();

        $this->options = array_extend($this->options, $options);

        $file = $this->saveOrigin($file);

        $filters = $this->getFilters();
        foreach($filters as $key => $filter) {
            $image = Image::make($file)->orientate();

            $image->filter($filter);
            $image->save($this->getFullFilePath($key));
        }

        return [
            'storage_hash'  => $this->storage_hash,
            'extension'     => $this->options['ext'],
            'originalName'  => $originalName,
            'type'          => $type,
            'size'          => $size,
        ];
    }
}
