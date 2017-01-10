<?php namespace App\Presenters;

use App\Tutor;
use App\UserBackgroundCheck;

class TutorBackgroundChecksPresenter extends AbstractPresenter
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
        $backgrounds = $tutor->backgroundCheck;

        $status = null;
        foreach($backgrounds as $background) {
            $isPending   = $background && $background->admin_status == UserBackgroundCheck::ADMIN_STATUS_PENDING;
            $isExpired   = $background && UserBackgroundCheck::isExpired($background);
            $isApproved  = $background && $background->admin_status == UserBackgroundCheck::ADMIN_STATUS_APPROVED;

            if(!$isExpired && $isApproved) {
                $status = 'approved';
                break;
            } elseif ($isPending) {
                $status = 'pending';
            }
        }

        return $status;
    }

    protected function includeDbs(Tutor $tutor)
    {
        if(!$dbs = $tutor->backgroundCheckWithType(UserBackgroundCheck::TYPE_DBS_CHECK)->first()) {return null;}

        return $this->item(
            $dbs,
            (new BackgroundCheckPresenter())->setDefaultIncludes(['image'])
        );
    }

    protected function includeDbsUpdate(Tutor $tutor)
    {
        if(!$dbsUpdate = $tutor->backgroundCheckWithType(UserBackgroundCheck::TYPE_DBS_UPDATE)->first()) {return null;}

        return $this->item(
            $dbsUpdate,
            (new BackgroundCheckPresenter())->setDefaultIncludes(['image'])
        );
    }

}
