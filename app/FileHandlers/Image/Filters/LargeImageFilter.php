<?php namespace App\FileHandlers\Image\Filters;

use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;

class LargeImageFilter implements FilterInterface
{
    const DIMENSIONS = 180;

    public function applyFilter(Image $image)
    {
        $image->fit(static::DIMENSIONS, static::DIMENSIONS);

        return $image;
    }

}
