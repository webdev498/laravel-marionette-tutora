<?php namespace App\Repositories;

use App\Tutor;
use App\Events\RaisesEvents;
use App\Events\UserWasEdited;
use App\UserQualificationOther as Other;
use App\UserQualificationAlevel as Alevel;
use App\UserQualificationTeacherStatus as QTS;
use App\UserQualificationUniversity as University;
use Illuminate\Database\DatabaseManager as Database;
use App\Database\Exceptions\ResourceNotDeletedException;
use App\Repositories\Contracts\QualificationRepositoryInterface;

class EloquentQualificationRepository implements QualificationRepositoryInterface
{

    use RaisesEvents;

    /**
     * @var Database
     */
    protected $database;

    /**
     * @var University
     */
    protected $university;

    /**
     * @var Alevel
     */
    protected $alevel;

    /**
     * @var Other
     */
    protected $other;

    /**
     * Create an instance of this repository.
     *
     * @param  Database   $database
     * @param  University $university
     * @param  Alevel     $alevel
     * @param  Other      $other
     * @return void
     */
    public function __construct(
        Database   $database,
        University $university,
        Alevel     $alevel,
        Other      $other
    ) {
        $this->database   = $database;
        $this->university = $university;
        $this->alevel     = $alevel;
        $this->other      = $other;
    }

    /**
     * Delete all qualifications belonging to a user
     *
     * @param  Tutor $tutor
     * @return void
     */
    public function deleteByTutor(Tutor $tutor)
    {
        return $this->database->transaction(function () use ($tutor) {
            $tutor->qualificationUniversities()->delete();
            $tutor->qualificationAlevels()->delete();
            $tutor->qualificationOthers()->delete();
        });
    }

    /**
     * Sync given qualifications with a given tutor
     *
     * @param  Tutor $tutor
     * @param  Array $universities
     * @param  Array $alevels
     * @param  Array $others
     * @return self
     */
    public function syncWithTutor(
        Tutor $tutor,
        Array $universities,
        Array $alevels,
        Array $others
    ) {
        return $this->database->transaction(function () use (
            $tutor,
            $universities,
            $alevels,
            $others
        ) {
            // Delete
            $this->deleteByTutor($tutor);
            // Save
            $tutor->qualificationUniversities()->saveMany($universities);
            $tutor->qualificationAlevels()->saveMany($alevels);
            $tutor->qualificationOthers()->saveMany($others);
            // Event
            $this->raise(new UserWasEdited($tutor));
            // Return
            return $this;
        });
    }

    /**
     * Sync the given QTS with a given tutor
     *
     * @param  Tutor $tutor
     * @param  QTS   $qts
     * @return self
     */
    public function syncQtsWithTutor(Tutor $tutor, QTS $qts)
    {
        return $this->database->transaction(function () use (
            $tutor,
            $qts
        ) {
            // Delete
            if ($tutor->qualificationTeacherStatus) {
                $tutor->qualificationTeacherStatus()->delete();
            }
            // Save
            $tutor->qualificationTeacherStatus()->save($qts);
            $tutor->load(['qualificationTeacherStatus']);
            // Event
            $this->raise(new UserWasEdited($tutor));
            // Return
            return $this;
        });
    }

}
