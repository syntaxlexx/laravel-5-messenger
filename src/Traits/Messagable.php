<?php

namespace Lexx\ChatMessenger\Traits;

use Lexx\ChatMessenger\Models\Message;
use Lexx\ChatMessenger\Models\Models;
use Lexx\ChatMessenger\Models\Participant;
use Lexx\ChatMessenger\Models\Thread;
use Illuminate\Database\Eloquent\Builder;

trait Messagable
{
    /**
     * Message relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     * @codeCoverageIgnore
     */
    public function messages()
    {
        return $this->hasMany(Models::classname(Message::class));
    }

    /**
     * Participants relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     * @codeCoverageIgnore
     */
    public function participants()
    {
        return $this->hasMany(Models::classname(Participant::class));
    }

    /**
     * Thread relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     *
     * @codeCoverageIgnore
     */
    public function threads()
    {
        return $this->belongsToMany(
            Models::classname(Thread::class),
            Models::table('participants'),
            'user_id',
            'thread_id'
        );
    }

    /**
     * Returns the new messages count for user.
     *
     * @return int
     */
    public function newThreadsCount()
    {
        return $this->threadsWithNewMessages()->count();
    }

    /**
     * Returns the new messages for user.
     *
     * @return int
     */
    public function unreadMessages()
    {
        return Message::unreadForUser($this->getKey())->get();
    }

    /**
     * Returns the new messages count for user.
     *
     * @return int
     */
    public function unreadMessagesCount()
    {
        return count($this->unreadMessages());
    }

    /**
     * Returns all threads with new messages.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function threadsWithNewMessages()
    {
        return $this->threads()
            ->where(function (Builder $q) {
                $q->whereIn(Models::table('threads') . '.id', $this->unreadMessages()->pluck('thread_id'));
            })
            ->get();
    }
    
    /**
     * Returns the user's starred threads.
     *
     * @return int
     */
    public function starred()
    {
        return $this->hasManyThrough(
            Models::table('threads'),
            Models::table('participants'),
            'thread_id',
            'user_id',
            'id',
            'id'
        );
    }
    
    /**
     * Returns the starred threads. An alias of starred
     *
     * @return int
     */
    public function favourites()
    {
        return $this->starred();
    }

    /**
     * Get name to use. Should be overridden in model to reflect your project
     * 
     * @return string $name
     */
    public function getNameAttribute()
    {
        if($this->attributes['name'])
            return $this->attributes['name'];
        
        if($this->username)
            return $this->username;
        
        if($this->first_name)
            return $this->first_name;

        // if none is found, just return the email
        return $this->email;
    }
}
