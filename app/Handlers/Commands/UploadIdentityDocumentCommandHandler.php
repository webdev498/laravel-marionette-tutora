<?php

namespace App\Handlers\Commands;

use Image;
use App\User;
use App\IdentityDocument;
use Illuminate\Auth\AuthManager as Auth;
use App\Billing\Contracts\BillingInterface;
use App\Validators\IdentityDocumentValidator;
use App\Commands\UploadIdentityDocumentCommand;
use Illuminate\Database\DatabaseManager as Database;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repositories\Contracts\IdentityDocumentRepositoryInterface;
use App\Database\Exceptions\ResourceNotFoundException;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Auth\Exceptions\UnauthorizedException;

class UploadIdentityDocumentCommandHandler extends CommandHandler
{

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
     * @var IdentityDocumentValidator
     */
    protected $validator;

    /**
     * @var IdentityDocumentRepositoryInterface
     */
    protected $identityDocuments;

    /**
     * @var BillingInterface
     */
    protected $billing;

    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * Crete an instance of the handler.
     *
     * @param  Database                            $database
     * @param  Auth                                $auth
     * @param  IdentityDocumentValidator           $validator
     * @param  IdentityDocumentRepositoryInterface $identityDocuments
     * @param  BillingInterface                    $billing
     * @param  UserRepositoryInterface $users
     */
    public function __construct(
        Database                            $database,
        Auth                                $auth,
        IdentityDocumentValidator           $validator,
        IdentityDocumentRepositoryInterface $identityDocuments,
        BillingInterface                    $billing,
        UserRepositoryInterface             $users
    ) {
        $this->database          = $database;
        $this->auth              = $auth;
        $this->validator         = $validator;
        $this->identityDocuments = $identityDocuments;
        $this->billing           = $billing;
        $this->users             = $users;
    }

    /**
     * Execute the command.
     *
     * @param  UploadIdentityDocumentCommand $command
     * @return IdentitiyDocument
     */
    public function handle(UploadIdentityDocumentCommand $command)
    {
        return $this->database->transaction(function() use ($command) {
            // invalid
            // @todo
            // Lookups
            $user = $this->findUser($command->uuid);

            // Guard
            $this->guardAgainstUnauthorized($user);

            $identityDocument = $user->identityDocument
                ? IdentityDocument::restore($user->identityDocument)
                : IdentityDocument::store($this->generateUuid());
            // Upload
            $image = $this->upload($command->file, $identityDocument);
            // Save
            $this->identityDocuments->saveForUser($user, $identityDocument);
            // Events
            $this->dispatchFor($identityDocument);
            // Return
            return $identityDocument;
        });
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
     * Guard against unauthorised editing of this user
     *
     * @throws UnauthorizedException
     * @param  User $user
     * @return void
     */
    protected function guardAgainstUnauthorized(User $user)
    {
        $authed = $this->auth->user();
        if ($authed && $authed->id !== $user->id && ! $authed->isAdmin()) {
            throw new UnauthorizedException();
        }
    }

    /**
     * Upload the given file
     *
     * @param  UploadedFile     $file
     * @param  IdentityDocument $identityDocument
     */
    protected function upload(
        UploadedFile     $file,
        IdentityDocument $identityDocument
    ) {
        $image = Image::make($file);
        return $image->save($identityDocument->path);
    }

    /**
     * Generate a uuid, ensuring that is is infact, unique
     *
     * @return string
     */
    protected function generateUuid()
    {
        do {
            $uuid = str_uuid(true);
        } while ($this->identityDocuments->countByUuid($uuid) > 0);

        return $uuid;
    }
}
