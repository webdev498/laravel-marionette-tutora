<?php

namespace App\Transformers;

use App\User;
use App\Tutor;
use App\Image;
use Carbon\Carbon;
use App\UserBackgroundCheck;
use App\Transformers\Files\ImageTransformer;

class BackgroundCheckTransformer extends AbstractTransformer
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'user',
        'image',
    ];

    /**
     * Turn this object into a generic array
     *
     * @param  UserBackgroundCheck $backgroundCheck
     *
     * @return array
     */
    public function transform(UserBackgroundCheck $backgroundCheck = null)
    {
        if(!$backgroundCheck) { return []; }

        $statusTitle = trans('background_check.dbs_admin_statuses')[$backgroundCheck->admin_status];

        $createdAt = new Carbon($backgroundCheck->created_at);
        $formattedCreatedAt = $this->formatHumanTime($createdAt);

        $issuedAt = $backgroundCheck->issued_at ? new Carbon($backgroundCheck->issued_at) : null;
        $formattedIssuedAt = $this->formatDob($issuedAt);

        $dob = $backgroundCheck->dob ? new Carbon($backgroundCheck->dob) : null;
        $formattedDob = $this->formatDob($dob);

        $isExpired = $issuedAt < UserBackgroundCheck::getExpiredDate();
        $status = $isExpired ? UserBackgroundCheck::STATUS_EXPIRED : '';

        return [
            'uuid'               => (string) $backgroundCheck->uuid,
            'admin_status'       => $backgroundCheck->admin_status,
            'rejected_for'       => $backgroundCheck->rejected_for,
            'reject_comment'     => $backgroundCheck->reject_comment,
            'status'             => $status,
            'status_title'       => $statusTitle,
            'type'               => $backgroundCheck->type,
            'certificate_number' => (string) $backgroundCheck->certificate_number,
            'last_name'          => (string) $backgroundCheck->last_name,
            'dob'                => $formattedDob,
            'issued_at'          => $formattedIssuedAt,
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
    protected function includeUser(UserBackgroundCheck $backgroundCheck = null)
    {
        if(!$backgroundCheck) return null;

        $user = $backgroundCheck->user;

        return $this->item($user, new UserTransformer());
    }

    /**
     * Include image data
     *
     * @param  UserBackgroundCheck $backgroundCheck
     *
     * @return Item
     */
    protected function includeImage(UserBackgroundCheck $backgroundCheck = null)
    {
        if(!$backgroundCheck) return null;

        $image = $backgroundCheck->image;

        return $image ? $this->item($image, new ImageTransformer()) : null;
    }

    /**
     * Format the users DOB
     *
     * @param \DateTime $dob
     * @return mixed
     */
    protected function formatDob(\DateTime $dob = null)
    {
        return $dob ? $dob->format('d/m/Y') : null;
    }
}
