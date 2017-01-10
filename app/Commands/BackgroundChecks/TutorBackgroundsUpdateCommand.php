<?php namespace App\Commands\BackgroundChecks;

use App\Commands\Command;

class TutorBackgroundsUpdateCommand extends Command
{

    /**
     * @param $uuid
     * @param array|null $dbs
     * @param array|null $dbsUpdate
     */
    public function __construct(
        $uuid,
        Array $dbs = null,
        Array $dbsUpdate = null
    ) {
        $this->uuid      = $uuid;
        $this->dbs       = $dbs;
        $this->dbsUpdate = $dbsUpdate;
    }


}