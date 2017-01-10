<?php

namespace App\Transformers;

use App\User;
use App\Tutor;
use App\Image;
use Carbon\Carbon;
use App\UserBackgroundCheck;
use App\Transformers\Files\ImageTransformer;

class TutorBackgroundChecksTransformer extends AbstractTransformer
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'dbs',
        'dbs_update',
    ];

    /**
     * Turn this object into a generic array
     *
     * @param  Tutor $tutor
     *
     * @return array
     */
    public function transform(Tutor $tutor)
    {
        $status = $this->getBackgroundStatus($tutor);

        return [
            'background_status' => $status,
        ];
    }

    protected function getBackgroundStatus(Tutor $tutor)
    {
        $dbs       = $tutor->backgroundCheckWithType(UserBackgroundCheck::TYPE_DBS_CHECK)->first();
        $dbsUpdate = $tutor->backgroundCheckWithType(UserBackgroundCheck::TYPE_DBS_UPDATE)->first();

        $isDbsPending       = $dbs && $dbs->admin_status == UserBackgroundCheck::ADMIN_STATUS_PENDING;
        $isDbsUpdatePending = $dbsUpdate && $dbsUpdate->admin_status == UserBackgroundCheck::ADMIN_STATUS_PENDING;

        $isDbsApproved       = $dbs && $dbs->admin_status == UserBackgroundCheck::ADMIN_STATUS_APPROVED;
        $isDbsUpdateApproved = $dbsUpdate && $dbsUpdate->admin_status == UserBackgroundCheck::ADMIN_STATUS_APPROVED;

        $isDbsExpired       = $dbs && UserBackgroundCheck::isExpired($dbs);
        $isDbsUpdateExpired = $dbsUpdate && UserBackgroundCheck::isExpired($dbsUpdate);

        $status = null;
        if((!$isDbsExpired && $isDbsApproved) || (!$isDbsUpdateExpired && $isDbsUpdateApproved)) {
            $status = 'approved';
        } elseif ($isDbsPending || $isDbsUpdatePending) {
            $status = 'pending';
        }

        return $status;
    }

    protected function includeDbs(Tutor $tutor)
    {
        if(!$dbs = $tutor->backgroundCheckWithType(UserBackgroundCheck::TYPE_DBS_CHECK)->first()) {return null;}

        return $this->item(
            $dbs,
            (new BackgroundCheckTransformer())->setDefaultIncludes(['image'])
        );
    }

    protected function includeDbsUpdate(Tutor $tutor)
    {
        if(!$dbsUpdate = $tutor->backgroundCheckWithType(UserBackgroundCheck::TYPE_DBS_UPDATE)->first()) {return null;}

        return $this->item(
            $dbsUpdate,
            (new BackgroundCheckTransformer())->setDefaultIncludes(['image'])
        );
    }
}
