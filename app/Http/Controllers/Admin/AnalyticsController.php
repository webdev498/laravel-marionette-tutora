<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Repositories\Contracts\TutorRepositoryInterface;
use Illuminate\Http\Request;

class AnalyticsController extends AdminController
{
    public function index(
        TutorRepositoryInterface $tutor
    ) {
        $tutors = $tutor->countLive();

        return view('admin.analytics.index', compact('tutors'));
    }
}
