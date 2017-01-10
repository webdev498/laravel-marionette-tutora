<?php namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use App\UserQualificationTeacherStatus;

class QualificationTeacherStatusController extends ApiController
{

    public function edit(Request $request)
    {
        $qts = $request->user()->qualificationTeacherStatus;

        if ( ! $qts) {
            $qts = new UserQualificationTeacherStatus($request->input());
            $request->user()->qualificationTeacherStatus()->save($qts);
        } else {
            $qts->fill($request->input());
            $qts->save();
        }

        return $qts;
    }

}
