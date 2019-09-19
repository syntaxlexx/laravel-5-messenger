<?php

namespace App\Http\Controllers;

use App\Events\MessageWasComposed;
use App\User;
use Carbon\Carbon;
use Lexx\ChatMessenger\Models\Message;
use Lexx\ChatMessenger\Models\Participant;
use Lexx\ChatMessenger\Models\Thread;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;


class MessagesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Show all of the message threads to the user.
     *
     * @return mixed
     */
    public function index()
    {
        // All threads, ignore deleted/archived participants
        // $threads = Thread::getAllLatest()->get();

        // All threads that user is participating in
        $threads = Thread::forUser(Auth::id())->latest('updated_at')->get();

        // All threads that user is participating in, with new messages
        // $threads = Thread::forUserWithNewMessages(Auth::id())->latest('updated_at')->get();

        return view('messenger.index', compact('threads'));
    }

    /**
     * Shows a message thread.
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        try {
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Session::flash('error_message', 'The thread with ID: ' . $id . ' was not found.');

            return redirect()->route('messages');
        }

        // show current user in list if not a current participant
        // $users = User::whereNotIn('id', $thread->participantsUserIds())->get();

        // don't show the current user in list
        $userId = Auth::id();
        $users = User::whereNotIn('id', $thread->participantsUserIds($userId))->get();

        $thread->markAsRead($userId);

        return view('messenger.show', compact('thread', 'users'));
    }

    /**
     * Creates a new message thread.
     *
     * @return mixed
     */
    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();

        return view('messenger.create', compact('users'));
    }

    /**
     * Stores a new message thread.
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $thread = Thread::create([
            'subject' => $input['subject'],
        ]);

        // Message
        $message = Message::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'body' => $input['message'],
        ]);

        // Sender
        Participant::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'last_read' => new Carbon,
        ]);

        // Recipients
        if ($request->has('recipients')) {
            // add code logic here to check if a thread has max participants set
            // utilize either $thread->getMaxParticipants()  or $thread->hasMaxParticipants()

            $thread->addParticipant($input['recipients']);
        }

        // check if pusher is allowed
        if (config('chatmessenger.use_pusher')) {
            $this->oooPushIt($message);
        }

        if (request()->ajax()) {
            return response()->json([
                'status' => 'OK',
                'message' => $message,
                'thread' => $thread,
            ]);
        }

        return redirect()->route('messages');
    }

    /**
     * Adds a new message to a current thread.
     *
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        try {
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Session::flash('error_message', 'The thread with ID: ' . $id . ' was not found.');

            return redirect()->route('messages');
        }

        // Restore all participants within a thread that has a new message.
        // we do not need it since when we remove a participant we do not wish the user to receive the message
        // $thread->activateAllParticipants();

        // Message
        $message = Message::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'body' => $request->get('message'),
        ]);

        // Add replier as a participant
        $participant = Participant::firstOrCreate([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
        ]);
        $participant->last_read = new Carbon;
        $participant->save();

        // Recipients
        if ($request->has('recipients')) {
            // add code logic here to check if a thread has max participants set
            // utilize either $thread->getMaxParticipants()  or $thread->hasMaxParticipants()

            $thread->addParticipant($request->get('recipients'));
        }

        $html = view('messenger.partials.html-message', compact('message'))->render();

        // check if pusher is allowed
        if (config('chatmessenger.use_pusher')) {
            $this->oooPushIt($message);
        }

        if (request()->ajax()) {
            return response()->json([
                'status' => 'OK',
                'message' => $message,
                'html' => $html,
                'thread_subject' => $message->thread->subject,
            ]);
        }

        return redirect()->route('messages.show', $id);
    }

    /**
     * Send the new message to Pusher in order to notify users.
     *
     * @param Message $message
     * @return void
     */
    protected function oooPushIt(Message $message, $html = '')
    {
        $thread = $message->thread;
        $sender = $message->user;

        $data = [
            'thread_id' => $thread->id,
            'div_id' => 'thread_' . $thread->id,
            'sender_name' => $sender->name,
            'thread_url' => route('messages.show', ['id' => $thread->id]),
            'thread_subject' => $thread->subject,
            'message' => $message->body,
            'html' => $html,
            'text' => str_limit($message->body, 50),
        ];

        $recipients = $thread->participantsUserIds();
        if (count($recipients) > 0) {
            foreach ($recipients as $recipient) {
                if ($recipient == $sender->id) {
                    continue;
                }

                // previous way of doing it
                // $pusher_resp = Pusher::trigger(['for_user_' . $recipient], 'new_message', $data);
                
                // new way of doing it
                event(new MessageWasComposed($recipient, $data));
                
                // We're done here - how easy was that, it just works!
            }
        }
    }

    /**
     * Mark a specific thread as read, for ajax use.
     *
     * @param $id
     */
    public function read($id)
    {
        $thread = Thread::find($id);
        if (!$thread) {
            abort(404);
        }

        $thread->markAsRead(Auth::id());
    }

    /**
     * Get the number of unread threads, for ajax use.
     *
     * @return array
     */
    public function unread()
    {
        $count = Auth::user()->unreadMessagesCount();

        return ['msg_count' => $count];
    }
}