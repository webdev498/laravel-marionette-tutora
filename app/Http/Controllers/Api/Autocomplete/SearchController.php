<?php

namespace App\Http\Controllers\Api\Autocomplete;

use App\User;
use App\Tutor;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Repositories\Contracts\UserRepositoryInterface;

class SearchController extends ApiController
{

    protected $users;

    public function __construct(UserRepositoryInterface $users)
    {
        $this->users = $users;
    }

    public function index(Request $request)
    {
        // Options
        $query = $request->get('query', null);
        // 404
        if ( ! $query) {
            abort(400);
        }

        // Lookup
        $users = $this->users
            ->getByQuery($query);

        // Present
        $users = $this->transformCollection(
            $users,
            function (User $user) {
                return [
                    'data'  => route(
                        ($user instanceof Tutor ? 'admin.tutors.show' : 'admin.students.show'),
                        [
                            'uuid' => $user->uuid,
                        ]
                    ),
                    'value' => sprintf(
                        '%s %s (%s)',
                        $user->first_name,
                        $user->last_name,
                        $user->uuid
                    ),
                ];
            }
        );
        // Return
        return $users;
    }

}
