<?php

namespace App\Http\Controllers\Student;

use App\Auth\Exceptions\UnauthorizedException;
use App\Commands\RecordTransgressionCommand;
use App\Commands\SendMessageCommand;
use App\Dialogue\UserDialogue;
use App\Dialogue\UserDialogueInteraction;
use App\Events\MessageWasRead;
use App\Pagination\MessagesPaginator;
use App\Presenters\MessagePresenter;
use App\Relationship;
use App\Repositories\Contracts\MessageRepositoryInterface;
use App\Toast;
use App\Validators\Exceptions\ValidationException;
use Illuminate\Auth\AuthManager as Auth;
use Illuminate\Http\Request;

class MessagesController extends StudentController
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
     * @var MessagesPaginator
     */
    protected $paginator;

    /**
     * Create an instance of the controller
     *
     * @param  Auth                       $auth
     * @param  MessageRepositoryInterface $messages
     * @param  MessagesPaginator          $paginator
     *
     * @return void
     */
    public function __construct(
        Auth $auth,
        MessageRepositoryInterface $messages,
        MessagesPaginator $paginator
    ) {
        $this->auth      = $auth;
        $this->messages  = $messages;
        $this->paginator = $paginator;
    }

    /**
     * Show the messages to the user
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        // Options
        $page    = (int)$request->get('page', 1);
        $perPage = MessagesPaginator::PER_PAGE;
        // Lookups
        $student = $this->auth->user();
        $items   = $this->messages->getByUser($student, $page, $perPage);
        $count   = $this->messages->countByUser($student);
        // Paginate
        $messages = $this->paginator->paginate($items, $count, $page, [
            'path' => relroute('student.messages.index')
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
                'meta'    => [
                    'count'      => $messages->count(),
                    'total'      => $count,
                    'pagination' => $messages->render(),
                ],
            ]
        );

        // Return
        return view('student.messages.index', compact('messages'));
    }

    /**
     * Show a message to the user.
     *
     * @param  Request $request
     * @param          $uuid
     * @param null     $dialogue
     *
     * @return Response
     */
    public function show(Request $request, $uuid, $dialogue = null)
    {
        try {
            // Lookups
            $student = $this->auth->user();
            $message = $this->messages->findByUuid($uuid);

            // Guard
            if (!$message || $message->relationship->student->id !== $student->id) {
                throw new UnauthorizedException();
            }

            loginfo($dialogue);
            // Dialogues
            if ($dialogue != "student_first_message"
                && $message->countReplies() == 1
                && !UserDialogueInteraction::existsForUser($student, "student_first_message")
            ) {
                return UserDialogue::Show("student_first_message", [$message->uuid],
                    route("student.messages.show", ["id" => $message->uuid], false));
            }

            if ($dialogue != "first_reply"
                && $message->countSenderLines() > 1
                && $message->relationship->status != Relationship::MISMATCHED
                && !UserDialogueInteraction::existsForUser($student, "first_reply")
            ) {
                return UserDialogue::Show("first_reply", [$message->uuid],
                    route("student.messages.show", ["id" => $message->uuid], false));
            }

            // Event
            event(new MessageWasRead($message, $student));

            // Present
            $message = $this->presentItem($message, new MessagePresenter(), [
                'include' => [
                    'lines',
                    'relationship',
                ],
            ]);

            // Return
            return view('student.messages.show', compact('message'));
        } catch (UnauthorizedException $e) {
            return abort(404);
        }
    }

    /**
     * Create a new message.
     *
     * @param  Request $request
     * @param  String  $uuid
     *
     * @return Response
     */
    public function store(Request $request, $uuid)
    {
        try {
            $message = $this->dispatchFrom(SendMessageCommand::class, $request, [
                'uuid' => $uuid,
            ]);

            return redirect()
                ->route('student.messages.show', [
                    'uuid' => $message->uuid,
                ])
                ->with([
                    'toast' => new Toast('Message sent.', Toast::SUCCESS),
                ]);
        } catch (UnauthorizedException $e) {
            return redirect()
                ->route('student.messages.index')
                ->with([
                    'toast' => new Toast($e->getMessage(), Toast::ERROR),
                ]);
        } catch (ValidationException $e) {
            // Record
            if ($e->getErrors()[0]['code'] == 'ERR_CONTACT') {
                $attempt = $this->dispatchFrom(RecordTransgressionCommand::class, $request, [
                    'uuid' => $uuid,
                ]);
            }

            // Redirect

            return redirect()
                ->route('student.messages.show', [
                    'uuid' => $uuid
                ])
                ->with([
                    'toast'  => new Toast("Couldn't send that message. Please check the form for errors and try again",
                        Toast::ERROR),
                    'errors' => $e->getErrorBag()
                ])
                ->withInput();
        }
    }

}
