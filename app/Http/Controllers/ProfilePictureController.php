<?php namespace App\Http\Controllers;

use Image;
use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;

class ProfilePictureController extends Controller
{

    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function show(
        Request $request,
        $name,
        $width,
        $height,
        $ext
    ) {
        $dir = storage_path().'/app/profile-pictures';

        $requested = templ(':dir/:name@:widthx:height.:ext', [
            'dir'    => $dir,
            'name'   => $name,
            'width'  => $width,
            'height' => $height,
            'ext'    => $ext,
        ]);

        $default = templ(':dir/:name@:widthx:height.:ext', [
            'dir'    => $dir,
            'name'   => 'default',
            'width'  => $width,
            'height' => $height,
            'ext'    => $ext,
        ]);

        if ($this->filesystem->exists($requested)) {
            $file = $requested;
        } elseif ($this->filesystem->exists($default)) {
            $file = $default;
        }

        if ( ! isset($file)) {
            abort(404);
        }

        return Image::make($file)->response();
    }
}
