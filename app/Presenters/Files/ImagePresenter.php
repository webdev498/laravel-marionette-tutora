<?php namespace App\Presenters\Files;

use App\Image;
use League\Fractal\ParamBag;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use App\Transformers\SubjectsTransformer;
use App\FileHandlers\Image\ImageUploader;

class ImagePresenter extends TransformerAbstract
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'paths',
    ];

    /**
     * @param array $options
     */
    public function __construct(
        $options = []
    )
    {
        if (($includes = array_get($options, 'include', false)) !== false) {
            $this->defaultIncludes = $includes;
        }
    }

    /**
     * Turn this object into a generic array
     *
     * @param Image $image
     *
     * @return array
     */
    public function transform(Image $image)
    {
        return [
            'uuid' => (string)  $image->uuid,
        ];
    }

    protected function includePaths(Image $image)
    {
        $imageUploader = new ImageUploader();

        return $this->item($image, function ($image) use ($imageUploader) {
            $imageUploader->setImage($image);
            $paths = $imageUploader->getFilenames();

            return $paths;
        });
    }

}
