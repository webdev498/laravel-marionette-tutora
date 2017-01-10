<?php namespace App\Image\Filters;

use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;

class ProfilePictureLargeFilter implements FilterInterface
{
    const DIMENSIONS = 180;

    public function applyFilter(Image $image)
    {
        $image->fit(static::DIMENSIONS, static::DIMENSIONS);

        return $image;
    }

}
