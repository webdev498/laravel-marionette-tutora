<?php

namespace App\Handlers\Commands\BackgroundChecks;

use App\User;
use App\Admin;
use App\Tutor;
use App\Image;
use App\UserBackgroundCheck;
use App\Handlers\Commands\CommandHandler;
use App\Commands\BackgroundChecks\TutorBackgroundUpdateCommand;
use App\Validators\BackgroundChecks\UpdateTutorBackgroundValidator;
use App\Billing\Contracts\BillingInterface;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Database\DatabaseManager as Database;
use App\Database\Exceptions\ResourceNotFoundException;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\ImageRepositoryInterface;
use App\Database\Exceptions\DuplicateResourceException;
use App\Repositories\Contracts\BackgroundCheckRepositoryInterface;
use App\Auth\Exceptions\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TutorBackgroundUpdateCommandHandler extends TutorBackgroundCommandHandler
{
    /**
     * Create an instance of the handler.
     *
     * @param  Database                             $database
     * @param  Auth                                 $auth
     * @param  UpdateTutorBackgroundValidator       $validator
     * @param  UserRepositoryInterface              $users
     * @param  BackgroundCheckRepositoryInterface   $backgroundChecks
     * @param  ImageRepositoryInterface             $images
     */
    public function __construct(
        Database                            $database,
        Auth                                $auth,
        UpdateTutorBackgroundValidator      $validator,
        UserRepositoryInterface             $users,
        BackgroundCheckRepositoryInterface  $backgroundChecks,
        ImageRepositoryInterface            $images
    ) {
        parent::__construct(
            $database,
            $auth,
            $validator,
            $users,
            $backgroundChecks,
            $images
        );
    }

    /**
     * Execute the command.
     *
     * @param  TutorBackgroundUpdateCommand $command
     *
     * @return User
     */
    public function handle(TutorBackgroundUpdateCommand $command)
    {
        return $this->database->transaction(function () use ($command) {
            // Validation
            $this->guardAgainstInvalidData($command);

            // Lookups
            $user = $this->findUser($command->uuid);

            // Guard
            $this->guardAgainstUnauthorized($user, true);

            $background = $this->updateBackgroundCheck($user, $command);

            // Dispatch
            $this->dispatchFor($this->dispatch);

            // Return
            return $background;
        });
    }

    /**
     * Update a users Background Check.
     *
     * @param User $user
     * @param TutorBackgroundUpdateCommand $command
     *
     * @return User
     */
    protected function updateBackgroundCheck(
        User $user,
        TutorBackgroundUpdateCommand $command
    ) {
        // Attributes
        $attributes = array_to_object((array) $command);

        if($command->type == 'dbs') {
            $background = $this->updateDbs($user, $attributes);
        }

        if($command->type == 'dbs_update') {
            $background = $this->updateDbsUpdate($user, $attributes);
        }

        // Return
        return $background;
    }

    /**
     * @param User   $user
     * @param Object $data
     *
     * @return UserBackgroundCheck
     */
    private function updateDbsUpdate(User $user, $data)
    {
        $type = UserBackgroundCheck::TYPE_DBS_UPDATE;

        $background = $this->findBackgroundCheck($user, $type);

        if(!$background) {
            $background = $this->newDbsUpdate($user);
        }

        // Issued at change
        if ($data->issued_at) {
            $background = $this->updateBackgroundIssuedAt($background, $data->issued_at);
        }

        // Status change
        if ($data->admin_status) {
            $background = $this->updateBackgroundAdminStatus($background, $data->admin_status);
        }

        // Rejected for change
        if ($data->rejected_for) {
            $background->rejected_for = (int)$data->rejected_for;
        }

        $background->dob                = \DateTime::createFromFormat('d/m/Y', $data->dob);
        $background->certificate_number = $data->certificate_number;
        $background->last_name          = $data->last_name;

        $this->backgroundChecks->save($background);

        // Dispatch
        $this->dispatch[] = $background;

        return $background;
    }

    /**
     * @param User   $user
     * @param Object $data
     *
     * @return UserBackgroundCheck
     */
    private function updateDbs(User $user, $data)
    {
        $type = UserBackgroundCheck::TYPE_DBS_CHECK;

        $background = $this->findBackgroundCheck($user, $type);

        if(!$background) {
            $background = $this->newDbs($user);
        }

        $imageUuid = $data->image_upload ? $data->image_upload->uuid : null;

        if($imageUuid) {
            $image = $this->images->findByUuid($imageUuid);
            $this->attachNewImage($background, $image);
        }

        // Issued at change
        if ($data->issued_at) {
            $background = $this->updateBackgroundIssuedAt($background, $data->issued_at);
        }

        // Status change
        if ($data->admin_status) {
            $background = $this->updateBackgroundAdminStatus($background, $data->admin_status);
        }

        // Rejected for change
        if ($data->rejected_for) {
            $background->rejected_for = (int)$data->rejected_for;
        }

        // Rejected for change
        if ($data->reject_comment) {
            $background->reject_comment = $data->reject_comment;
        }

        $this->backgroundChecks->save($background);

        // Dispatch
        $this->dispatch[] = $background;

        return $background;
    }

}
