<?php

namespace App\Http\Controllers\Admin\Relationships;

use App\Toast;
use Illuminate\Http\Request;
use App\Presenters\MessagePresenter;
use App\Commands\SendMessageCommand;
use App\Presenters\RelationshipPresenter;
use App\Auth\Exceptions\UnauthorizedException;
use App\Http\Controllers\Admin\AdminController;
use App\Validators\Exceptions\ValidationException;
use App\Repositories\Contracts\MessageRepositoryInterface;
use App\Repositories\Contracts\RelationshipRepositoryInterface;

class MessagesController extends AdminController
{
    /**
     * @var RelationshipRepositoryInterface
     */
    protected $relationships;

    /**
     * @var MessageRepositoryInterface
     */
    protected $messages;

    /**
     * Create an instance of the controller
     *
     * @param  RelationshipRepositoryInterface $relationships
     * @param  MessageRepositoryInterface      $messages
     * @return void
     */
    public function __construct(
        RelationshipRepositoryInterface $relationships,
        MessageRepositoryInterface      $messages
    ) {
        $this->relationships = $relationships;
        $this->messages      = $messages;
    }

    /**
     * Show a message
     *
     * @param  integer $id
     * @return Response
     */
    public function show($id)
    {
        // Lookups
        $relationship = $this->relationships->findByIdOrFail($id);
        $message      = $this->messages
            ->with([
                'relationship',
                'lines:count(100)',
                'lines.user',
            ])
            ->findByRelationshipOrFail($relationship);
        // Present
        $relationship = $this->presentItem(
            $relationship,
            new RelationshipPresenter(),
            [
                'include' => [
                    'tutor',
                    'student',
                ],
            ]
        );
        
        $message = $this->presentItem(
            $message,
            new MessagePresenter(),
            [
                'include' => [
                    'lines',
                    'relationship',
                ],
            ]
        );

        // Return
        return view('admin.relationships.messages.show', compact('relationship', 'message'));
    }

    /**
     * Store a new message
     *
     * @param  Request $request
     * @param  integer $id
     * @return Redirect
     */
    public function store(Request $request, $id)
    {
        try {
            // Lookups
            $relationship = $this->relationships->findByIdOrFail($id);
            // Dispatch
            $message = $this->dispatchFrom(SendMessageCommand::class, $request,  [
                'relationship' => $relationship,
                'from_system'  => true,
            ]);
            // Return
            return redirect()
                ->route('admin.relationships.messages.show', compact('id'));
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->with([
                    'toast' => new Toast("Couldn't send that message.", Toast::ERROR),
                    'errors' => $e->getErrorBag()
                ]);
        } catch (UnauthorizedException $e) {
            return redirect()
                ->back()
                ->with([
                    'toast' => new Toast($e->getMessage(), Toast::ERROR),
                ]);
        }
    }
}
