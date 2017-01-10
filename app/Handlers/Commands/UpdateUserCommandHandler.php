<?php

namespace App\Handlers\Commands;

use App\User;
use App\Admin;
use App\Tutor;
use App\Address;
use App\UserProfile;
use App\IdentityDocument;
use App\Commands\UpdateUserCommand;
use App\UserRequirement;
use App\UserRequirementCollection;
use App\Validators\UpdateUserValidator;
use App\Billing\Contracts\BillingInterface;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Database\DatabaseManager as Database;
use App\Database\Exceptions\ResourceNotFoundException;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\ImageRepositoryInterface;
use App\Database\Exceptions\DuplicateResourceException;
use App\Auth\Exceptions\UnauthorizedException;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Ziggeo;

class UpdateUserCommandHandler extends CommandHandler
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
     * @var UpdateUserValidator
     */
    protected $validator;

    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * @var BillingInterface
     */
    protected $billing;

    /**
     * The Ziggeo SDK.
     *
     * @var Ziggeo
     */
    protected $ziggeo;

    /**
     * Create an instance of the handler.
     *
     * @param  Database                 $database
     * @param  Auth                     $auth
     * @param  UpdateUserValidator      $validator
     * @param  UserRepositoryInterface  $users
     * @param  ImageRepositoryInterface $images
     * @param  BillingInterface         $billing
     * @param Ziggeo                    $ziggeo
     */
    public function __construct(
        Database $database,
        Auth $auth,
        UpdateUserValidator $validator,
        UserRepositoryInterface $users,
        ImageRepositoryInterface $images,
        BillingInterface $billing,
        Ziggeo $ziggeo
    ) {
        $this->database  = $database;
        $this->auth      = $auth;
        $this->validator = $validator;
        $this->users     = $users;
        $this->images    = $images;
        $this->billing   = $billing;
        $this->ziggeo    = $ziggeo;
    }

    /**
     * Execute the command.
     *
     * @param  UpdateUserCommand $command
     *
     * @return User
     */
    public function handle(UpdateUserCommand $command)
    {

        return $this->database->transaction(
            function () use ($command) {

                // Validation
                $this->guardAgainstInvalidData($command);

                // Lookups
                $user = $this->findUser($command->uuid);
                // Guard
                $this->guardAgainstUnauthorized($user);

                // Addresses
                if ($command->addresses) {
                    $user = $this->updateAddresses(
                        $user,
                        $command
                    );
                }
                // Profile
                if ($command->profile) {
                    $user = $this->updateProfile(
                        $user,
                        $command
                    );
                }
                // Identification
                if ($command->identity_document) {
                    $user = $this->updateIdentification(
                        $user,
                        $command
                    );
                }
                // Bank
                if ($command->bank) {
                    $user = $this->updateBank(
                        $user,
                        $command
                    );
                }
                // Card
                if ($command->card !== null) {
                    $user = $this->updateCard(
                        $user,
                        $command
                    );
                }
                // Status
                if ($command->status !== null) {
                    $user = $this->updateStatus(
                        $user,
                        $command
                    );
                }
                // Settings
                if ($command->settings !== null) {
                    $user = $this->updateSettings(
                        $user,
                        $command
                    );
                }

                // Reset password
                if (!empty($command->reset_password)) {
                    $user = $this->updatePassword(
                        $user,
                        $command
                    );
                }

                // Generic
                $user = User::edit(
                    $user,
                    $command->first_name,
                    $command->last_name,
                    $command->email,
                    $command->telephone,
                    $command->password
                );

                $this->dispatch[] = $user;
                // Save
                $this->users->save($user);

                // Dispatch
                $this->dispatchFor($this->dispatch);

                // Return
                return $user;
            }
        );
    }

    /**
     * Updates the user password
     *
     * @param User              $user
     * @param UpdateUserCommand $command
     *
     * @return User
     */
    protected function updatePassword(
        User $user,
        UpdateUserCommand $command
    ) {

        // the fields those corresponding arguments are empty are not updated
        $user = User::edit(
            $user,
            null,
            null,
            null,
            null,
            $command->reset_password
        );

        return $user;
    }

    /**
     * Update a users addresses
     *
     * @param  User              $user
     * @param  UpdateUserCommand $command
     *
     * @return User
     */
    protected function updateAddresses(
        User $user,
        UpdateUserCommand $command
    ) {
        // Update each address
        foreach ((array)$command->addresses as $name => $attributes) {
            if ($address = $user->addresses->{$name}) {
                // Data
                $attributes = array_to_object((array)$attributes);
                // Edit
                if ($attributes->line_1 && $attributes->line_2 && $attributes->postcode) {
                    $address = Address::edit(
                        $address,
                        $attributes->line_1,
                        $attributes->line_2,
                        $attributes->line_3,
                        $attributes->postcode
                    );
                    // Dispatch
                    $this->dispatch[] = $address;
                }
            }
        }

        // Return
        return $user;
    }

    /**
     * Update a users profile
     *
     * @param  User              $user
     * @param  UpdateUserCommand $command
     *
     * @return User
     */
    protected function updateProfile(
        User $user,
        UpdateUserCommand $command
    ) {
        // Data
        $profile    = $user->profile;
        $attributes = array_to_object((array)$command->profile);

        // Status change
        if ($attributes->status) {
            $profile = $this->updateProfileStatus(
                $profile,
                $attributes->status
            );
        }
        // Admin status change
        if ($attributes->admin_status) {
            $profile = $this->updateProfileAdminStatus(
                $profile,
                $attributes->admin_status
            );
        }
        // Quality
        if ($attributes->quality) {
            $profile = $this->updateProfileQuality(
                $profile,
                $attributes->quality
            );
        }
        // Summary
        if ($attributes->summary) {
            $profile = $this->updateProfileSummary(
                $profile,
                $attributes->summary
            );
        }
        // Featured
        if ($attributes->featured !== null) {
            $profile = $this->updateProfileFeatured(
                $profile,
                $attributes->featured
            );
        }
        // Video
        if ($this->isVideoStatusCanceledOrDeleted($attributes->videoStatus)) {
            $this->deleteVideo(
                $user,
                $user->requirements
            );
        }

        // Edit

        if ($attributes->tagline
            || $attributes->summary
            || $attributes->rate
            || $attributes->travel_radius != null
            || $attributes->bio
            || $attributes->short_bio
            || $attributes->videoStatus
        ) {
            $profile = UserProfile::edit(
                $profile,
                $attributes->tagline,
                $attributes->summary,
                $attributes->rate,
                $attributes->travel_radius,
                $attributes->bio,
                $attributes->short_bio,
                $attributes->videoStatus
            );
        }

        // Dispatch
        $this->dispatch[] = $profile;

        // Return
        return $user;
    }

    /**
     * Update a user profile status
     *
     * @param  UserProfile $profile
     * @param  string      $status
     *
     * @return UserProfile
     */
    protected function updateProfileStatus(UserProfile $profile, $status)
    {
        switch ($status) {
            case UserProfile::EXPIRED:
                $profile = UserProfile::expire($profile);
                break;

            case UserProfile::LIVE:
                $profile = UserProfile::live($profile);
                break;

            case UserProfile::OFFLINE:
                $profile = UserProfile::offline($profile);
                break;
        }

        return $profile;
    }

    /**
     * Update a user profile admin status
     *
     * @param  UserProfile $profile
     * @param  string      $status
     *
     * @return UserProfile
     */
    protected function updateProfileAdminStatus(UserProfile $profile, $status)
    {
        if ($this->auth->user() instanceof Admin) {
            switch ($status) {
                case UserProfile::OK:
                    $profile = UserProfile::ok($profile);
                    break;

                case UserProfile::REJECTED:
                    $profile = UserProfile::rejected($profile);
                    break;
            }
        }

        return $profile;
    }

    /**
     * Update a user profile quality
     *
     * @param  UserProfile $profile
     * @param  string      $quality
     *
     * @return UserProfile
     */
    protected function updateProfileQuality(UserProfile $profile, $quality)
    {
        if ($this->auth->user() instanceof Admin) {
            $profile->quality = $quality;
        }

        return $profile;
    }

    /**
     * Update a user profile featured
     *
     * @param  UserProfile $profile
     * @param  string      $featured
     *
     * @return UserProfile
     */
    protected function updateProfileFeatured(UserProfile $profile, $featured)
    {
        if ($this->auth->user() instanceof Admin) {
            $profile->featured = $featured;
        }

        return $profile;
    }

    /**
     * Update user profile summary
     *
     * @param  UserProfile $profile
     * @param  string      $summary
     *
     * @return UserProfile
     */
    protected function updateProfileSummary(UserProfile $profile, $summary)
    {
        if ($this->auth->user() instanceof Admin) {
            $profile->summary = $summary;
        }

        return $profile;
    }

    /**
     * Update a users settings.
     *
     * @param                    User
     * @param  UpdateUserCommand $command
     *
     * @return UserProfile
     */
    protected function updateSettings(
        User $user,
        UpdateUserCommand $command
    ) {
        $authed = $this->auth->user();
        if ($authed && $authed->isAdmin()) {
            // Attributes
            $attributes = array_to_object((array)$command->settings);
            // Lookups
            $settings = $user->settings;
            // Update

            foreach ($attributes as $attribute => $value) {
                $settings->{$attribute} = $value;
            }
        }

        // Return
        return $user;
    }

    /**
     * Update a users identification
     *
     * @param  User              $user
     * @param  UpdateUserCommand $command
     *
     * @return User
     */
    protected function updateIdentification(
        User $user,
        UpdateUserCommand $command
    ) {
        // Guard
        // $this->guardAgainstChangingLegalInfromation()
        $attributes = array_to_object((array)$command->identity_document);
        // Lookups
        $identityDocument = $this->findIdentityDocument(
            $user,
            $attributes->uuid
        );
        // Update
        $user = User::legal(
            $user,
            $command->legal_first_name,
            $command->legal_last_name,
            $this->dobToDate($command->dob)
        );
        // Identity Document
        $this->dispatchFor[] = IdentityDocument::restore($identityDocument);

        // Return
        return $user;
    }

    /**
     * Update a users bank account
     *
     * @param  User              $user
     * @param  UpdateUserCommand $command
     *
     * @return User
     */
    protected function updateBank(
        User $user,
        UpdateUserCommand $command
    ) {
        // Update

        $bank = $this->billing->bank($user);
        if ($command->bank) {
            $bank->create($command->bank);
        }

        // Return
        return User::bank(
            $user,
            $bank
        );
    }

    /**
     * Update a users card
     *
     * @param  User              $user
     * @param  UpdateUserCommand $command
     *
     * @return User
     */
    protected function updateCard(
        User $user,
        UpdateUserCommand $command
    ) {
        // Update
        $card = $this->billing->card($user);
        if ($command->card) {
            $card->create($command->card);
        }

        // Return
        return User::card(
            $user,
            $card
        );
    }

    /**
     * Update a users status
     *
     * @param  User              $user
     * @param  UpdateUserCommand $command
     *
     * @return User
     */
    protected function updateStatus(User $user, $command)
    {
        if ($this->auth->user() instanceof Admin) {
            $user->status = $command->status;
        }

        return $user;
    }

    protected function isVideoStatusCanceledOrDeleted($videoStatus)
    {
        return $videoStatus === "canceled" || $videoStatus === "deleted";
    }

    protected function deleteVideo(User $user, UserRequirementCollection $requirements)
    {
        $this->ziggeo->videos()->delete('_' . $user->uuid);

        foreach ($requirements as $requirement) {
            if ($requirement->name === 'personal_video') {
                $requirement->is_pending   = true;
                $requirement->is_completed = false;
                $requirement->save();
            }
        }
    }

    /**
     * Find a user by a given uuid
     *
     * @throws ResourceNotFoundException
     *
     * @param  string $uuid
     *
     * @return User
     */
    protected function findUser($uuid)
    {
        $user = $this->users->findByUuid($uuid);

        if (!$user) {
            throw new ResourceNotFoundException();
        }

        return $user;
    }

    /**
     * Find an identity document by a given user and uuid
     *
     * @throws UnauthorizedException
     * @throws ResourceNotFoundException
     *
     * @param  User   $user
     * @param  string $uuid
     *
     * @return IdentityDocument
     */
    protected function findIdentityDocument(User $user, $uuid)
    {
        $identityDocument = $user->identityDocument;

        if (!$identityDocument) {
            throw new ResourceNotFoundException();
        }

        if ($identityDocument->uuid !== $uuid) {
            throw new UnauthorizedException();
        }

        return $identityDocument;
    }

    /**
     * Convert a given dob to a DateTime
     *
     * @param  Array
     *
     * @return DateTime|null
     */
    protected function dobToDate(Array $dob = null)
    {
        if (isset($dob)) {
            $parts = array_filter(
                [
                    array_get(
                        $dob,
                        'year'
                    ),
                    array_get(
                        $dob,
                        'month'
                    ),
                    array_get(
                        $dob,
                        'day'
                    ),
                ]
            );

            if (count($parts) === 3) {
                return strtodate(
                    implode(
                        '/',
                        $parts
                    )
                );
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
        $this->validator->validate((array)$command);
    }

    /**
     * Guard against unauthorised editing of this user
     *
     * @throws UnauthorizedException
     *
     * @param  User $user
     *
     * @return void
     */
    protected function guardAgainstUnauthorized(User $user)
    {
        $authed = $this->auth->user();
        if ($authed && $authed->id !== $user->id && !$authed->isAdmin()) {
            throw new UnauthorizedException();
        }
    }

    /**
     * Guard against using an existing email
     *
     * @throws DuplicateResourceException
     *
     * @return void
     */
    protected function guardAgainstDuplicateEmail($email)
    {
        if ($this->users->countByEmail($email) > 0) {
            throw new DuplicateResourceException(
                [
                    [
                        'field'  => 'email',
                        'detail' => trans(
                            'validation.unique',
                            ['attribute' => 'email']
                        )
                    ]
                ]
            );
        }
    }

}
