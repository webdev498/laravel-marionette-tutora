<?php namespace App\FileHandlers\Image\Filters;

use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;

class SmallImageFilter implements FilterInterface
{
    const DIMENSIONS = 80;

    public function applyFilter(Image $image)
    {
        $image->fit(static::DIMENSIONS, static::DIMENSIONS);

        return $image;
    }

}
