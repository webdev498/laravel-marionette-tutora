<?php

namespace App\Handlers\Commands\BackgroundChecks;

use App\User;
use App\Admin;
use App\Tutor;
use App\Image;
use App\UserBackgroundCheck;
use App\Handlers\Commands\CommandHandler;
use App\Commands\BackgroundChecks\TutorBackgroundCreateCommand;
use App\Validators\BackgroundChecks\CreateTutorBackgroundValidator;
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

class TutorBackgroundCreateCommandHandler extends TutorBackgroundCommandHandler
{
    /**
     * Create an instance of the handler.
     *
     * @param  Database                             $database
     * @param  Auth                                 $auth
     * @param  CreateTutorBackgroundValidator       $validator
     * @param  UserRepositoryInterface              $users
     * @param  BackgroundCheckRepositoryInterface   $backgroundChecks
     * @param  ImageRepositoryInterface             $images
     */
    public function __construct(
        Database                            $database,
        Auth                                $auth,
        CreateTutorBackgroundValidator      $validator,
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
     * @param  TutorBackgroundCreateCommand $command
     *
     * @return User
     */
    public function handle(TutorBackgroundCreateCommand $command)
    {
        return $this->database->transaction(function () use ($command) {
            // Validation
            $this->guardAgainstInvalidData($command);

            // Lookups
            $user = $this->findUser($command->uuid);

            // Guard
            $this->guardAgainstUnauthorized($user);

            $background = $this->createBackgroundCheck($user, $command);

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
     * @param TutorBackgroundCreateCommand $command
     *
     * @return UserBackgroundCheck $background
     */
    protected function createBackgroundCheck(
        User $user,
        TutorBackgroundCreateCommand $command
    ) {
        // Attributes
        $attributes = array_to_object((array) $command);

        $background = null;

        if($command->type == 'dbs') {
            $background = $this->createDbs($user, $attributes);
        } elseif ($command->type == 'dbs_update') {
            $background = $this->createDbsUpdate($user, $attributes);
        }

        // Issued at change
        if ($attributes->issued_at) {
            $background = $this->updateBackgroundIssuedAt($background, $attributes->issued_at);
        }

        // Status change
        if ($attributes->admin_status) {
            $background = $this->updateBackgroundAdminStatus($background, $attributes->admin_status);
        }

        $this->backgroundChecks->save($background);

        // Dispatch
        $this->dispatch[] = $background;

        // Return
        return $background;
    }

    /**
     * @param User   $user
     * @param Object $data
     *
     * @return UserBackgroundCheck
     */
    private function createDbs(User $user, $data)
    {
        $type = UserBackgroundCheck::TYPE_DBS_CHECK;

        $prevBackground = $this->findBackgroundCheck($user, $type);
        if($prevBackground) {
            $prevBackground->delete();
        }

        $background = $this->newDbs($user);

        $imageUuid = $data->image_upload ? $data->image_upload->uuid : null;

        if($imageUuid) {
            $image = $this->images->findByUuid($imageUuid);
            $this->attachNewImage($background, $image);
        }

        return $background;
    }

    /**
     * @param User   $user
     * @param Object $data
     *
     * @return UserBackgroundCheck
     */
    private function createDbsUpdate(User $user, $data)
    {
        $type = UserBackgroundCheck::TYPE_DBS_UPDATE;

        $prevBackground = $this->findBackgroundCheck($user, $type);
        if($prevBackground) {
            $prevBackground->delete();
        }

        $background = $this->newDbsUpdate($user);

        $background->dob                = \DateTime::createFromFormat('d/m/Y', $data->dob);
        $background->certificate_number = $data->certificate_number;
        $background->last_name          = $data->last_name;

        return $background;
    }

}
