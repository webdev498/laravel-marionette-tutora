<?php namespace App\Handlers\Commands\Subscriptions;

use App\Commands\Subscriptions\UnsubscribeUserCommand;
use App\Handlers\Commands\CommandHandler;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\User;
use App\UserSubscription;
use Illuminate\Database\DatabaseManager;
use Vinkla\Hashids\Facades\Hashids;

class UnsubscribeUserCommandHandler extends CommandHandler
{

    /**
     * @var DatabaseManager
     */
    protected $database;

    /**
     * @var UserRepositoryInterface
     */    
    protected $users;

    /*
     * Create the command handler.
     *
     * @param  DatabaseManager         $database
     * @return void
     */
    public function __construct(
        DatabaseManager         $database,
        UserRepositoryInterface $users
    ) {
        $this->database  = $database;
        $this->users = $users;
    }

    /**
     * Handle the command.
     *
     * @param  UnsubscribeUserCommand  $command
     * @return void
     */
    public function handle(UnsubscribeUserCommand $command)
    {
        

        // Lookups
        $user_id = HashIds::connection('subscriptions')->decode($command->token)[0];
        $user = $this->users->findById($user_id);      

        // Update  
        $subscription = $user->subscription;
        if ($subscription->unsubscribe($command->list)) {
            $subscription->save();
            return true;
        }
        return false;

    }


}
