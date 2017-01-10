<?php namespace App\Transformers;

use League\Fractal\Resource\Item;
use League\Fractal\Manager as Fractal;
use League\Fractal\Resource\Collection;
use App\Presenters\Serializer\PresenterArraySerializer;

trait TransformerTrait
{

    /**
     * Get the fractal instance
     */
    protected function fractal()
    {
        return app()->make(Fractal::class);
    }

    /**
     * Transform an item.
     *
     * @param  Mixed    $item
     * @param  Callable $callback
     * @param  Array    $options
     * @return Array|Object
     */
    protected function transformItem($item, $callback, Array $options = [])
    {
        $options = array_extend([
            'wrap_in_data' => true,
            'to_object'    => false,
        ], $options);

        $fractal  = new Fractal();
        $resource = new Item($item, $callback);

        if (array_get($options, 'wrap_in_data', true) === false) {
            $fractal->setSerializer(new PresenterArraySerializer());
        }

        if (($includes = array_get($options, 'include', false)) !== false) {
            $fractal->parseIncludes($includes);
        }

        if (($meta = array_get($options, 'meta', false)) !== false) {
            foreach ($meta as $key => $value) {
                $resource->setMetaValue($key, $value);
            }
        }

        $scope = $fractal->createData($resource);
        $array = $scope->toArray();

        if (array_get($options, 'to_object', false) !== false) {
            $array = array_to_object($array);
        }

        return $array;
    }

    protected function transformCollection($collection, $callback, Array $options = [])
    {
        $options = array_extend([
            'wrap_in_data' => true,
            'to_object'    => false,
        ], $options);

        $fractal  = new Fractal();
        $resource = new Collection($collection, $callback);

        if (array_get($options, 'wrap_in_data', true) === false) {
            $fractal->setSerializer(new PresenterArraySerializer());
        }

        if (($includes = array_get($options, 'include', false)) !== false) {
            $fractal->parseIncludes($includes);
        }

        if (($meta = array_get($options, 'meta', false)) !== false) {
            $resource->setMeta($meta);
        }

        $scope = $fractal->createData($resource);
        $array = $scope->toArray();

        if (array_get($options, 'to_object', false) !== false) {
            $array = array_map('array_to_object', $array);
            $array = array_to_object($array);
        }

        return $array;
    }

}
