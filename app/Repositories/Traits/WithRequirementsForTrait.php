<?php

namespace App\Repositories\Traits;

trait WithRequirementsForTrait
{

    protected function withRequirementsFor($query, $for)
    {
        return $query
            ->where('for', '=', $for)
            ->orWhere('for', '=', null);
    }

}
