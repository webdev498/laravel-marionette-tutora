<?php

namespace App\Http\Controllers\Admin\Jobs;

use Illuminate\Http\Request;
use App\Presenters\JobPresenter;
use App\Http\Controllers\Admin\AdminController;
use App\Repositories\Contracts\JobRepositoryInterface;
use Pheanstalk\Job;

class DetailsController extends AdminController
{

    /**
     * @var JobRepositoryInterface
     */
    protected $jobs;

    /**
     * Create an instance of the controller
     *
     * @param JobRepositoryInterface $job
     */
    public function __construct(
        JobRepositoryInterface $job
    ) {
        $this->jobs = $job;
    }

    /**
     * Show the Job.
     *
     * @param  integer $uuid
     *
     * @return Response
     */
    public function edit($uuid)
    {
        // Lookup
        $job = $this->jobs->findByUuid($uuid);

        if(!$job) {
            abort(404);
        }

        // Return
        return view('admin.jobs.details.edit');
    }
}
