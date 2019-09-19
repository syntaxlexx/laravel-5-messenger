<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Lexx\ChatMessenger\Models\Thread;
use Session;
use Auth;

class ThreadController extends Controller
{
    protected $model;

    public function __construct(Thread $model)
    {
        $this->middleware('auth');
        $this->model = $model;
    }

    /**
     * Remove a participant from a thread
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addParticipant($id, $userId)
    {
        $thread = $this->model->findOrFail($id);
        if($thread->addParticipant($userId))
        {
            Session::flash('success', 'Participant added successfully');
        } else {
            Session::flash('error', 'There was an error adding the participant');
        }

        return redirect()->back();
    }

    /**
     * Remove a participant from a thread
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removeParticipant($id, $userId)
    {
        $thread = $this->model->findOrFail($id);
        if($thread->removeParticipant($userId))
        {
            Session::flash('success', 'Participant removed successfully');
        } else {
            Session::flash('error', 'There was an error removing the participant');
        }

        return redirect()->back();
    }

    /**
     * Star a thread
     * 
     * @param  int  $threadId
     * @return \Illuminate\Http\Response
     */
    public function star($id)
    {
        $thread = $this->model->findOrFail($id);

        if($thread->star())
        {
            Session::flash('success', 'Thread starred');
        } else {
            Session::flash('error', 'There was an error starring the thread');
        }

        return redirect()->back();
    }
    
    
    /**
     * Unstar a thread
     * 
     * @param  int  $threadId
     * @return \Illuminate\Http\Response
     */
    public function unstar($id)
    {
        $thread = $this->model->findOrFail($id);

        if($thread->unstar())
        {
            Session::flash('success', 'Thread unstarred');
        } else {
            Session::flash('error', 'There was an error starring the thread');
        }

        return redirect()->back();
    }
}
