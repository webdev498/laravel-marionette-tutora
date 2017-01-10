<?php

namespace App\Repositories;

use App\Database\Model;

abstract class AbstractEloquentRepository
{
    protected $page = null;

    protected $perPage = null;

    protected $orderBy = null;

    protected $order = null;

    protected $with = [];

    protected function orFail($resource)
    {
        // Throw
        if ( ! $resource) {
            throw new ModelNotFoundException();
        }
        // Return
        return $resource;
    }

    public function paginate($page, $perPage)
    {
        $this->page    = $page;
        $this->perPage = $perPage;

        return $this;
    }

    public function orderBy($column, $order = 'desc')
    {
        $this->column = $column;
        $this->order = $order;

        return $this;
    }

    public function with($with = [])
    {
        $this->with = [];

        if (! is_array($with))
        {
            $this->with[] = $with;
        }

        if (is_array($with))
        {

            foreach ($with as $name) {
                if (str_contains($name, ':')) {
                    list ($key, $scope) = explode(':', $name);

                    $arguments = [];
                    if (str_contains($scope, '(')) {
                        list($scope, $arguments) = explode('(', substr($scope, 0, -1));
                        $arguments = explode(',', $arguments);
                    }

                    $method = camel_case(str_replace('.', '_', "with_{$key}_{$scope}"));

                    $this->with[$key] = function($query) use ($method, $arguments) {
                        array_unshift($arguments, $query);
                        return call_user_func_array([$this, $method], $arguments);
                    };
                } else {
                    $this->with[] = $name;
                }
            }
        }


        return $this;
    }

    public function load(Model $model)
    {
        return $model->load($this->with);
    }
}
