<?php namespace App\Http\Controllers\Files;

use Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Filesystem\Filesystem;

class ImageController extends Controller
{

    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function show(
        Request $request,
        $filter,
        $path,
        $filename,
        $ext
    ) {
        $dir = storage_path().'/images';

        $requested = templ(':dir/:filter/:path/:filename', [
            'dir'       => $dir,
            'filter'    => $filter,
            'path'      => $path,
            'filename'  => $filename,
            'ext'       => $ext,
        ]);

        $default = templ(':dir/:filter/:path/:filename.:ext', [
            'dir'       => $dir,
            'filter'    => $filter,
            'path'      => 'defaults',
            'filename'  => 'default',
            'ext'       => $ext,
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
