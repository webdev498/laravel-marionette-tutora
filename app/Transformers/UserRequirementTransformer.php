<?php

namespace App\Transformers;

use App\UserRequirement;
use League\Fractal\TransformerAbstract;
use App\Transformers\Traits\UserRequirementTrait;

class UserRequirementTransformer extends TransformerAbstract
{
    use UserRequirementTrait;

    /**
     * Turn this object into a generic array
     *
     * @param  UserRequirement $collection
     * @return array
     */
    public function transform(UserRequirement $requirement)
    {
        return [
            'name'         => (string)  $requirement->name,
            'section'      => (string)  $requirement->section,
            'url'          => (string)  $this->getRequirementUrl($requirement),
            'is_optional'  => (boolean) $requirement->is_optional,
            'is_pending'   => (boolean) $requirement->is_pending,
            'is_completed' => (boolean) $requirement->is_completed,
            'title'        => (string)  trans("requirements.{$requirement->name}"),
            'sort'         => array_search($requirement->name, $this->order),
        ];
    }

}
