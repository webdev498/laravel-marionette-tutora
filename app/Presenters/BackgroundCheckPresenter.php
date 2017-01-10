<?php

namespace App\Presenters;

use App\User;
use App\Image;
use Carbon\Carbon;
use App\UserBackgroundCheck;
use App\Presenters\Files\ImagePresenter;
use Faker\Provider\DateTime;

class BackgroundCheckPresenter extends AbstractPresenter
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'image',
        'user',
    ];

    /**
     * Turn this object into a generic array
     *
     * @param  UserBackgroundCheck $backgroundCheck
     *
     * @return array
     */
    public function transform(UserBackgroundCheck $backgroundCheck)
    {
        $typeTitle = trans('background_check.background_check_types')[$backgroundCheck->type];

        $createdAt = new Carbon($backgroundCheck->created_at);
        $formattedCreatedAt = $this->formatHumanTime($createdAt);

        if ($backgroundCheck->dob) {
            $dob = new \DateTime($backgroundCheck->dob);
        } else {
            $dob= null;
        }
        $formattedDob = $this->formatDob($dob);

        return [
            'uuid'               => (string) $backgroundCheck->uuid,
            'admin_status'       => $backgroundCheck->admin_status,
            'type_title'       => $typeTitle,
            'type'               => $backgroundCheck->type,
            'certificate_number' => (string) $backgroundCheck->certificate_number,
            'last_name'          => (string) $backgroundCheck->last_name,
            'dob'                => $formattedDob,
            'issued_at'          => (string) $backgroundCheck->issued_at,
            'created_at'         => $formattedCreatedAt,
        ];
    }

    /**
     * Include user data
     *
     * @param  UserBackgroundCheck $backgroundCheck
     *
     * @return Item
     */
    protected function includeUser(UserBackgroundCheck $backgroundCheck)
    {
        $user = $backgroundCheck->user;

        return $this->item($user, new UserPresenter());
    }

    /**
     * Include image data
     *
     * @param  UserBackgroundCheck $backgroundCheck
     *
     * @return Item
     */
    protected function includeImage(UserBackgroundCheck $backgroundCheck)
    {
        $image = $backgroundCheck->image;

        return $image ? $this->item($image, new ImagePresenter()) : null;
    }

    /**
     * Format the users DOB
     *
     * @param \DateTime $dob
     * @return mixed
     */
    protected function formatDob($dob)
    {
        return $dob ? $dob->format('d/m/Y') : null;
    }
}
