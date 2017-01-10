<?php

namespace App\Handlers\Commands;

use App\Commands\FormRelationshipCommand;
use App\Relationship;
use App\Repositories\Contracts\RelationshipRepositoryInterface;
use App\Repositories\Contracts\SearchRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Search;
use App\Student;
use App\Tutor;
use App\User;
use Illuminate\Database\DatabaseManager;

class FormRelationshipCommandHandler extends CommandHandler
{
    /**
     * @var Database
     */
    protected $database;

    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * @var RelationshipRepositoryInterface
     */
    protected $relationships;

    /**
     * @var SearchRepositoryInterface
     */
    protected $searches;

    /**
     * Create an instance of the command handler.
     *
     * @param  DatabaseManager                 $database
     * @param  UserRepositoryInterface         $users
     * @param  RelationshipRepositoryInterface $relationships
     * @return void
     */
    public function __construct(
        DatabaseManager                 $database,
        UserRepositoryInterface         $users,
        RelationshipRepositoryInterface $relationships,
        SearchRepositoryInterface       $searches
    ) {
        $this->database      = $database;
        $this->users         = $users;
        $this->relationships = $relationships;
        $this->searches      = $searches;
    }

    /**
     * Handle the command.
     *
     * @param  FormRelationshipCommand $command
     * @return LessonBooking
     */
    public function handle(FormRelationshipCommand $command)
    {
        return $this->database->transaction(function () use ($command) {
            // Validate
            // @todo
            // Lookups
            $tutor   = $this->findUser($command->tutor);
            $student = $this->findUser($command->student);
            $search = $command->search;
            // Guard
            if ( ! (
                $tutor instanceof Tutor &&
                $student instanceof Student
            )) {
                throw new \Exception();
            }
            // Find existing
            $relationship = $this->relationships->findByTutorAndStudent(
                $tutor,
                $student
            );
            // Make?
            if ( ! $relationship) {

                // Make
                $relationship = Relationship::make($tutor, $student);
                // Save
                $this->relationships->save($relationship);
                //Attach search 
                if ($search !== null) {
                    $this->searches->saveForRelationship($search, $relationship);
                }
                // Dispatch
                $this->dispatchFor($relationship);
                
            }
            // Return
            return $relationship;
        });
    }

    /**
     * Find a user by a given uuid.
     *
     * @param  mixed $uuid
     * @return User
     */
    public function findUser($uuid)
    {
        if ($uuid instanceof User) {
            return $uuid;
        }

        return $this->users->findByUuidOrFail($uuid);
    }
}
