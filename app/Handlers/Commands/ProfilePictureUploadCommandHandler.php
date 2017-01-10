<?php namespace App\Handlers\Commands;

use App\User;
use App\Tutor;
use App\Events\UserWasEdited;
use App\Image\ProfilePictureUploader;
use Illuminate\Contracts\Auth\Guard as Auth;
use App\Commands\ProfilePictureUploadCommand;
use App\Validators\ProfilePictureUploadValidator;
use Illuminate\Database\DatabaseManager as Database;
use App\Repositories\Contracts\UserRepositoryInterface;

class ProfilePictureUploadCommandHandler extends CommandHandler
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
     * @var ProfilePictureUploadValidator
     */
    protected $validator;

    /**
     * @var ProfilePictureUploader
     */
    protected $uploader;

    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * Create an instance of the handler.
     *
     * @param  Database                      $databaes
     * @param  Auth                          $auth
     * @param  ProfilePictureUploadValidator $validator
     * @return void
     */
    public function __construct(
        Database                           $database,
        Auth                               $auth,
        ProfilePictureUploadValidator      $validator,
        ProfilePictureUploader             $uploader,
        UserRepositoryInterface            $users
    ) {
        $this->database  = $database;
        $this->auth      = $auth;
        $this->validator = $validator;
        $this->uploader  = $uploader;
        $this->users     = $users;
    }

    /**
     * Execute the command.
     *
     * @param  ProfilePictureUploadCommand $command
     * @return User
     */
    public function handle(ProfilePictureUploadCommand $command)
    {
        return $this->database->transaction(function () use ($command) {
            $this->guardAgainstInvalidData($command);

            $user = $this->users->findByUuid($command->uuid);

            $this->uploader->upload($command->picture, [
                'uuid' => $user->uuid,
            ]);

            if ($user instanceof Tutor) {
                event(new UserWasEdited($user));
            }

            return array_map(function ($filename) {
                return '/img/profile-pictures/'.$filename;
            }, $this->uploader->getFilenames());
        });
    }

    /**
     * Guard against invalid data on the command.
     *
     * @param  ProfilePictureUploadCommand $command
     * @return void
     */
    protected function guardAgainstInvalidData(ProfilePictureUploadCommand $command)
    {
        return $this->validator->validate((array) $command);
    }

}
