<?php namespace App\Image;

use Image;
use App\User;
use App\Image\Filters\ProfilePictureSmallFilter;
use App\Image\Filters\ProfilePictureLargeFilter;

class ProfilePictureUploader
{

    protected $options = [
        'ext'  => 'jpg',
    ];

    public function getDirectory()
    {
        return storage_path().'/app/profile-pictures';
    }

    public function getFilenames()
    {
        $template = ':uuid@:sizex:size.:ext';

        return [
            'large' => templ($template, array_extend([
                'size' => ProfilePictureLargeFilter::DIMENSIONS,
            ], $this->options)),

            'small' => templ($template, array_extend([
                'size' => ProfilePictureSmallFilter::DIMENSIONS,
            ], $this->options)),
        ];
    }

    public function getFilename($key)
    {
        $filenames = $this->getFilenames();
        return array_get($filenames, $key);
    }

    public function upload($file, Array $options = [])
    {
        $this->options = array_extend($this->options, $options);

        $image = Image::make($file)->orientate();

        $image->filter(new ProfilePictureLargeFilter());
        $image->save($this->getDirectory().'/'.$this->getFilename('large'));

        $image->filter(new ProfilePictureSmallFilter());
        $image->save($this->getDirectory().'/'.$this->getFilename('small'));

        return true;
    }

}
