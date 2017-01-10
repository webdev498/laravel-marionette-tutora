<?php namespace App\Handlers\Commands;

use App\Auth\Exceptions\UnauthorizedException;
use App\Exceptions\TutorNotFoundException;
use App\Repositories\Contracts\TutorRepositoryInterface;
use App\Tutor;
use App\User;
use Illuminate\Database\DatabaseManager;
use App\Commands\DeleteTutorCommand;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Filesystem\Filesystem;

class DeleteTutorCommandHandler extends CommandHandler
{

    /**
     * @var DatabaseManager
     */
    protected $database;

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var TutorRepositoryInterface
     */
    protected $tutors;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @param DatabaseManager $database
     * @param Auth $auth
     * @param TutorRepositoryInterface $tutors
     * @param Filesystem $filesystem
     */
    public function __construct(
        DatabaseManager                   $database,
        Auth                              $auth,
        TutorRepositoryInterface          $tutors,
        Filesystem                        $filesystem
    ) {
        $this->database  = $database;
        $this->auth      = $auth;
        $this->tutors    = $tutors;
        $this->filesystem   = $filesystem;
    }

    /**
     * Handle the command.
     *
     * @param  DeleteTutorCommand  $command
     * @return $tutor
     */
    public function handle(DeleteTutorCommand $command)
    {
        return $this->database->transaction(function () use ($command) {
            $this->guardAgainstUserNotPermitted();

            $tutor = $this->findTutor($command->uuid);

            // Set tutor to deleted
            $tutor = Tutor::trash($tutor);

            $this->removeSensitiveData($tutor);
            $this->removeStripeAccount($tutor);

            $tutor->save();

            return $tutor;
        });
    }

    /**
     * @param $uuid
     * @return mixed
     * @throws TutorNotFoundException
     */
    protected function findTutor($uuid)
    {
        $tutor = $this->tutors->findByUuid($uuid);

        if ( ! $tutor) {
            throw new TutorNotFoundException();
        }

        return $tutor;
    }

    /**
     * @param User $user
     * @throws UnauthorizedException
     */
    protected function guardAgainstUserNotPermitted()
    {
        if ( ! $this->auth->user()->isAdmin()) {
            throw new UnauthorizedException();
        }
    }

    /**
     * @param Tutor $tutor
     * @throws \Exception
     */
    public function removeSensitiveData(Tutor $tutor)
    {
        // Identity Document ID
        $document = $tutor->identityDocument;

        if ($document) {
            $path = $document->path;
            if ( ! $this->filesystem->delete($path)) {
                throw new \Exception('Could not delete ID document for '.$tutor->uuid);
            }

            $document->destroy($document->id);
        }

        // Profile Picture ID
        $base_dir =  storage_path().'/app/profile-pictures/';
        $largeProfilePath = $base_dir . $tutor->uuid . '@180x180.jpg';
        $smallProfilePath = $base_dir . $tutor->uuid . '@80x80.jpg';

        if ($this->filesystem->exists($largeProfilePath)) {
            $this->filesystem->delete($largeProfilePath);
        }

        if ($this->filesystem->exists($smallProfilePath)) {
            $this->filesystem->delete($smallProfilePath);
        }           

            
    }

    /**
     * @param Tutor $tutor
     * @return bool
     */
    protected function removeStripeAccount(Tutor $tutor)
    {
        if ( ! $tutor->billing_id) {
            return false;
        }

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $account = \Stripe\Account::retrieve($tutor->billing_id);
        $account->delete();

        $tutor->billing_id = null;

        return $tutor;
    }

}
