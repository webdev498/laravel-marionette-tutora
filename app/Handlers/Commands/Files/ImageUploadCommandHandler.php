<?php namespace App\Handlers\Commands\Files;

use App\Image;
use App\Handlers\Commands\CommandHandler;
use App\FileHandlers\Image\ImageUploader;
use Illuminate\Contracts\Auth\Guard as Auth;
use App\Commands\Files\ImageUploadCommand;
use App\Validators\ImageUploadValidator;
use Illuminate\Database\DatabaseManager as Database;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\ImageRepositoryInterface;

class ImageUploadCommandHandler extends CommandHandler
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
     * @var ImageUploadValidator
     */
    protected $validator;

    /**
     * @var ImageUploader
     */
    protected $uploader;

    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * @var ImageRepositoryInterface
     */
    protected $images;

    /**
     * Create an instance of the handler.
     *
     * @param  Database                 $database
     * @param  Auth                     $auth
     * @param  ImageUploadValidator     $validator
     * @param  ImageUploader            $uploader
     * @param  UserRepositoryInterface  $users
     * @param  ImageRepositoryInterface $images
     */
    public function __construct(
        Database                  $database,
        Auth                      $auth,
        ImageUploadValidator      $validator,
        ImageUploader             $uploader,
        UserRepositoryInterface   $users,
        ImageRepositoryInterface  $images
    ) {
        $this->database  = $database;
        $this->auth      = $auth;
        $this->validator = $validator;
        $this->uploader  = $uploader;
        $this->users     = $users;
        $this->images    = $images;
    }

    /**
     * Execute the command.
     *
     * @param  ImageUploadCommand $command
     *
     * @return array
     */
    public function handle(ImageUploadCommand $command)
    {
        return $this->database->transaction(function () use ($command) {
            $this->guardAgainstInvalidData($command);

            $fileInfo = $this->uploader->upload($command->image);

            $uuid = $this->images->generateUuid();

            $image = Image::make(
                $uuid,
                $fileInfo['storage_hash'],
                $fileInfo['extension'],
                $fileInfo['originalName'],
                $fileInfo['type'],
                $fileInfo['size']
            );

            $this->images->save($image);

            return $image;
        });
    }

    /**
     * Guard against invalid data on the command.
     *
     * @param  ImageUploadCommand $command
     *
     * @return boolean
     */
    protected function guardAgainstInvalidData(ImageUploadCommand $command)
    {
        return $this->validator->validate((array) $command);
    }

}
