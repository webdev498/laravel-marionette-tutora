<?php namespace App\Presenters;

use League\Fractal\Resource\Item;
use League\Fractal\Manager as Fractal;
use League\Fractal\Resource\Collection;
use App\Presenters\Serializer\PresenterArraySerializer;

trait PresenterTrait
{

    public function presentItem($item, $callback, Array $options = [])
    {
        $options = array_extend([
            'wrap_in_data' => false,
            'to_object'    => true,
        ], $options);

        $fractal  = new Fractal();
        $resource = new Item($item, $callback);

        $fractal->setSerializer(new PresenterArraySerializer());
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

        if (
            array_get($options, 'meta', false) !== false ||
            array_get($options, 'wrap_in_data', false) === true
        ) {
            $meta = array_pull($array, 'meta', []);
            $array = [
                'data' => $array,
                'meta' => $meta,
            ];
        }

        if (array_get($options, 'to_object', false) !== false) {
            $array = array_to_object($array);
        }

        return $array;
    }

    public function presentCollection($collection, $callback, Array $options = [])
    {
        $options = array_extend([
            'wrap_in_data' => false,
            'to_object'    => true,
        ], $options);

        $fractal  = new Fractal();
        $resource = new Collection($collection, $callback);

        $fractal->setSerializer(new PresenterArraySerializer());

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

        if (
            array_get($options, 'meta', false) !== false ||
            array_get($options, 'wrap_in_data', false) === true
        ) {
            $meta = array_pull($array, 'meta', []);
            $array = [
                'data' => $array,
                'meta' => $meta,
            ];
        }

        if (array_get($options, 'to_object', false) !== false) {
            $array = array_map('array_to_object', $array);
            $array = array_to_object($array);
        }

        return $array;
    }

}
