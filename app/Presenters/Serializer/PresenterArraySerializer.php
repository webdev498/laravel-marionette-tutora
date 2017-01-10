<?php namespace App\Presenters\Serializer;

use League\Fractal\Serializer\ArraySerializer;

class PresenterArraySerializer extends ArraySerializer
{

    /**
     * Serialize a collection.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function collection($resourceKey, array $data)
    {
        return $data;
    }

}
