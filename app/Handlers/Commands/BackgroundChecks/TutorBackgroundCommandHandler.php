<?php

namespace App\Handlers\Commands\BackgroundChecks;

use App\User;
use App\Admin;
use App\Tutor;
use App\Image;
use App\UserBackgroundCheck;
use App\Handlers\Commands\CommandHandler;
use App\Commands\BackgroundChecks\TutorBackgroundCommand;
use App\Billing\Contracts\BillingInterface;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Database\DatabaseManager as Database;
use App\Database\Exceptions\ResourceNotFoundException;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\ImageRepositoryInterface;
use App\Database\Exceptions\DuplicateResourceException;
use App\Repositories\Contracts\BackgroundCheckRepositoryInterface;
use App\Validators\BackgroundChecks\TutorBackgroundValidatorInterface;
use App\Auth\Exceptions\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class TutorBackgroundCommandHandler extends CommandHandler
{

    /**
     * An array of objects to dispatch events off.
     *
     * @var array
     */
    protected $dispatch = [];

    /**
     * @var Database
     */
    protected $database;

    /**
     * The Guard implementation.
     *
     * @var Auth
     */
    protected $auth;

    /**
     * @var TutorBackgroundValidatorInterface
     */
    protected $validator;

    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * Create an instance of the handler.
     *
     * @param  Database                             $database
     * @param  Auth                                 $auth
     * @param  TutorBackgroundValidatorInterface    $validator
     * @param  UserRepositoryInterface              $users
     * @param  BackgroundCheckRepositoryInterface   $backgroundChecks
     * @param  ImageRepositoryInterface             $images
     */
    public function __construct(
        Database                            $database,
        Auth                                $auth,
        TutorBackgroundValidatorInterface   $validator,
        UserRepositoryInterface             $users,
        BackgroundCheckRepositoryInterface  $backgroundChecks,
        ImageRepositoryInterface            $images
    ) {
        $this->database         = $database;
        $this->auth             = $auth;
        $this->validator        = $validator;
        $this->users            = $users;
        $this->backgroundChecks = $backgroundChecks;
        $this->images           = $images;
    }

    /**
     * Attach new image and remove previous
     *
     * @param UserBackgroundCheck $background
     * @param Image $image
     */
    protected function attachNewImage(UserBackgroundCheck $background, Image $image)
    {
        $prevImage = $background->image;

        if($prevImage) {
            $prevImage->delete();
        }

        $background->image()->associate($image);
    }

    /**
     * Update a background status
     *
     * @param  UserBackgroundCheck $background
     * @param  string              $status
     *
     * @return UserBackgroundCheck
     */
    protected function updateBackgroundAdminStatus(UserBackgroundCheck $background, $status)
    {
        if(!$this->isAuthedAdmin()) {return $background;}

        switch ((int) $status) {
            case UserBackgroundCheck::ADMIN_STATUS_PENDING:
                $background = UserBackgroundCheck::pending($background);
                break;

            case UserBackgroundCheck::ADMIN_STATUS_APPROVED:
                $background = UserBackgroundCheck::approve($background);
                break;

            case UserBackgroundCheck::ADMIN_STATUS_REJECTED:
                $background = UserBackgroundCheck::reject($background);
                break;
        }

        return $background;
    }

    /**
     * Update a background issued at
     *
     * @param  UserBackgroundCheck $background
     * @param  string              $date
     *
     * @return UserBackgroundCheck
     */
    protected function updateBackgroundIssuedAt(UserBackgroundCheck $background, $date)
    {
        if(!$this->isAuthedAdmin()) {return $background;}

        $background->issued_at = \DateTime::createFromFormat('d/m/Y', $date);

        return $background;
    }

    /**
     * Create the users background check.
     *
     * @param  User     $user
     * @param  Image    $image
     *
     * @return UserBackgroundCheck
     */
    protected function newDbs(User $user, Image $image = null)
    {
        $newBackgroundCheckUuid = $this->backgroundChecks->generateUuid();

        $newBackground = UserBackgroundCheck::makeDbs(
            $user,
            $image,
            $newBackgroundCheckUuid
        );

        return $newBackground;
    }

    /**
     * Create the users background check.
     *
     * @param  User     $user
     * @param  Image    $image
     *
     * @return UserBackgroundCheck
     */
    protected function newDbsUpdate(User $user)
    {
        $newBackgroundCheckUuid = $this->backgroundChecks->generateUuid();

        $newBackground = UserBackgroundCheck::makeDbsUpdate(
            $user,
            $newBackgroundCheckUuid
        );

        return $newBackground;
    }

    /**
     * Find the users background check.
     *
     * @param  User     $user
     * @param  String   $type
     *
     * @return User
     */
    protected function findBackgroundCheck(User $user, $type)
    {
        $background = $this->backgroundChecks->getUserBackgroundCheckByType($user, $type);

        return $background;
    }

    /**
     * Find a user by a given uuid
     *
     * @throws ResourceNotFoundException
     * @param  string $uuid
     * @return User
     */
    protected function findUser($uuid)
    {
        $user = $this->users->findByUuid($uuid);

        if ( ! $user) {
            throw new ResourceNotFoundException();
        }

        return $user;
    }

    /**
     * Convert a given dob to a DateTime
     *
     * @param  Array
     *
     * @return \DateTime|null
     */
    protected function dobToDate(Array $dob = null)
    {
        if (isset($dob)) {
            $parts = array_filter([
                array_get($dob, 'year'),
                array_get($dob, 'month'),
                array_get($dob, 'day'),
            ]);

            if (count($parts) === 3) {
                return strtodate(implode('/', $parts));
            }
        }
    }

    /**
     * Guard against invalid data on the command
     *
     * @throws ValidationException
     * @return void
     */
    protected function guardAgainstInvalidData($command)
    {
        $this->validator->validate((array) $command);
    }

    /**
     * Guard against unauthorised editing of background
     *
     * @throws UnauthorizedException
     *
     * @param  User $user
     * @param  bool $adminRightsRequired
     *
     * @return void
     */
    protected function guardAgainstUnauthorized(User $user, $adminRightsRequired = false)
    {
        if($adminRightsRequired && !$this->isAuthedAdmin()) {
            throw new UnauthorizedException();
        }

        $authed = $this->auth->user();
        if ($authed && $authed->id !== $user->id && ! $authed->isAdmin()) {
            throw new UnauthorizedException();
        }
    }

    /**
     * Check if authed user has admin rights
     *
     * @return boolean
     */
    protected function isAuthedAdmin()
    {
        $authed = $this->auth->user();

        return $authed->isAdmin();
    }

}
