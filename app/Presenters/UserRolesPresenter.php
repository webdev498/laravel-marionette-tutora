<?php

namespace App\Presenters;

use App\Note;
use App\Role;
use League\Fractal\TransformerAbstract;

class UserRolesPresenter extends TransformerAbstract
{

    /**
     * Turn this object into a generic array
     *
     * @param  Role $role
     * @return array
     */
    public function transform(Role $role)
    {
        
        return [
            'name' => (string) $role->name,
        ];
    }

}

