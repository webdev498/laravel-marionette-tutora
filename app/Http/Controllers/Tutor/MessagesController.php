<?php namespace App\Http\Controllers\Tutor;

use App\Auth\Exceptions\UnauthorizedException;
use App\Commands\RecordTransgressionCommand;
use App\Commands\SendMessageCommand;
use App\Dialogue\UserDialogue;
use App\Dialogue\UserDialogueInteraction;
use App\Events\MessageWasRead;
use App\Pagination\MessagesPaginator;
use App\Presenters\MessagePresenter;
use App\Repositories\Contracts\MessageRepositoryInterface;
use App\Repositories\Contracts\RelationshipRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Toast;
use App\UserProfile;
use App\Validators\Exceptions\ValidationException;
use Illuminate\Auth\AuthManager as Auth;
use Illuminate\Http\Request;

class MessagesController extends TutorController
{

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var MessageRepositoryInterface
     */
    protected $messages;

    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * @var RelationshipRepositoryInterface
     */
    protected $relationships;

    /**
     * @var MessagesPaginator
     */
    protected $paginator;

    /**
     * Create an instance of the controller
     *
     * @param  Auth                            $auth
     * @param  MessageRepositoryInterface      $messages
     * @param  UserRepositoryInterface         $users
     * @param  RelationshipRepositoryInterface $relationships
     * @param  MessagesPaginator               $paginator
     * @return void
     */
    public function __construct(
        Auth                            $auth,
        MessageRepositoryInterface      $messages,
        UserRepositoryInterface         $users,
        RelationshipRepositoryInterface $relationships,
        MessagesPaginator               $paginator
    ) {
        $this->auth          = $auth;
        $this->messages      = $messages;
        $this->users         = $users;
        $this->relationships = $relationships;
        $this->paginator     = $paginator;
    }

    /**
     * Show the messages to the user
     *
     * @param  Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        // Options
        $page    = (int) $request->get('page', 1);
        $perPage = MessagesPaginator::PER_PAGE;
        // Lookups
        $tutor  = $this->auth->user();
        $items  = $this->messages->getByUser($tutor, $page, $perPage);
        $count  = $this->messages->countByUser($tutor);
        
        // Paginate
        $messages = $this->paginator->paginate($items, $count, $page, [
            'path' => relroute('tutor.messages.index')
        ]);
        // Present
        $messages = $this->presentCollection(
            $messages,
            new MessagePresenter(),
            [
                'include' => [
                    'lines',
                    'status',
                ],
                'meta' => [
                    'count'      => $messages->count(),
                    'total'      => $count,
                    'pagination' => $messages->render(),
                ],
            ]
        );

        // Return
        return view('tutor.messages.index', compact('messages'));
    }

    /**
     * Show a message to the user.
     *
     * @param  Request $request
     * @param $uuid
     * @param $dialogue
     * @return Response
     */
    public function show(Request $request, $uuid, $dialogue = NULL)
    {
        try {
            // Lookups
            $tutor   = $this->auth->user();
            $message = $this->messages->findByUuid($uuid);

            // Try student uuid
            if ( ! $message) {
                if ($student = $this->users->findByUuid($uuid)) {
                    $relationship = $this->relationships->findByTutorAndStudent($tutor, $student);
                    if ($relationship) {
                        return redirect()
                            ->route('tutor.messages.show', [
                                'uuid' => $relationship->message->uuid,
                            ]);
                    }
                }
            }

            if ($dialogue != "first_message" 
                && $this->messages->countByUser($tutor) == 1
                && !UserDialogueInteraction::existsForUser($tutor, "first_message"))
                return UserDialogue::Show("first_message", [$message->uuid], route("tutor.messages.show", ["id" => $message->uuid], false));

            // Event
            event(new MessageWasRead($message, $tutor));

            // Guard
            if ( ! $message || $message->relationship->tutor->id !== $tutor->id) {
                throw new UnauthorizedException();
            }
            // Present
            $message = $this->presentItem($message, new MessagePresenter(), [
                'include' => [
                    'relationship',
                    'lines',
                    'searches',
                    'searches.subject'
                ],
            ]);

            // Return
            return view('tutor.messages.show', compact('message'));
        } catch (UnauthorizedException $e) {
            return abort(404);
        }
    }

    /**
     * Create a new message.
     *
     * @param  Request $request
     * @param  String  $uuid
     * @return Response
     */
    
    public function store(Request $request, $uuid)
    {
        try {
            $message = $this->dispatchFrom(SendMessageCommand::class, $request, [
                'uuid' => $uuid,
            ]);

            $relationship = $message->relationship;
            $tutor = $relationship->tutor;

            $dialogue = null;
            $state = Toast::SUCCESS;
            switch($request->get('reason')) {
                case 'not_available':
                    $dialogue = [
                        'type' => 'profile_offline',
                        'message' => "Your profile is still live. Please make sure that you go offline if you cannot take on new students, as many people are looking for a tutor straight away. Just go to the 'Profile' tab on your dashboard to go offline, alternatively if you want to go offline now, click on the button below"
                    ];
                    break;

                case 'distance':
                    $distance = $tutor->distance ?: '?';
                    $address = implode(', ', array_filter([
                        $tutor->addresses->default->line_1,
                        $tutor->addresses->default->line_2,
                        $tutor->addresses->default->line_3,
                        $tutor->addresses->default->postcode
                    ]));

                    $dialogue = [
                        'type' => 'default',
                        'message' => "We will help this student find another tutor who can help them. Your travel policy is currently set at $distance miles and your address is $address. Please keep this up to date."
                    ];

                    break;

                case 'wrong_level':
                    $dialogue = [
                        'type' => 'default',
                        'message' => "Thanks. We will help this student find another tutor who can help them."
                    ];

                    break;

                case 'dont_tutor':
                    $profile = $tutor->profile;
                    UserProfile::offline($profile);
                    $profile->save();

                    $dialogue = [
                        'type' => 'default',
                        'message' => "We're sorry to hear that. We have taken your profile offline so no more students will get in touch with you.  Whenever you would like to start tutoring again, just visit the 'Profile' tab on your dashboard to go live once more."
                    ];

                    break;
            }

            return redirect()
                ->route('tutor.messages.show', [
                    'uuid' => $message->uuid,
                ])
                ->with([
                    'toast' => new Toast('Message sent.', $state),
                    'dialogue' => $dialogue
                ]);
        } catch (ValidationException $e) {
            
            if ($e->getErrors()[0]['code'] == 'ERR_CONTACT') {
                $attempt = $this->dispatchFrom(RecordTransgressionCommand::class, $request, [
                    'uuid' => $uuid,
                ]);
            }

            return redirect()
                ->route('tutor.messages.show', [
                    'uuid' => $uuid
                ])
                ->with([
                    'toast' => new Toast("Couldn't send that message. Please check the form for errors and try again.", Toast::ERROR),
                    'errors' => $e->getErrorBag()
                ])
                ->withInput();
                
        } catch (UnauthorizedException $e) {
            return redirect()
                ->route('tutor.messages.index')
                ->with([
                    'toast' => new Toast($e->getMessage(), Toast::ERROR),
                ]);
        }
    }

}
