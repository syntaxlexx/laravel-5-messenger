<?php

return [

    'user_model' => App\User::class,

    'message_model' => Lexx\ChatMessenger\Models\Message::class,

    'participant_model' => Lexx\ChatMessenger\Models\Participant::class,

    'thread_model' => Lexx\ChatMessenger\Models\Thread::class,

    /**
     * Define custom database table names - without prefixes.
     */
    'messages_table' => null,

    'participants_table' => null,

    'threads_table' => null,

    /**
     * Define custom database table names - without prefixes.
    */

    'use_pusher' => env('CHATMESSENGER_USE_PUSHER', false),

    /**
     * 
     */
    'defaults' => [

        /**
         * specify the default column to use in getting participant names 
         * $thread->participantsString($userId, $columns = [])
         */
        'participant_aka' => env('CHATMESSENGER_PARTICIPANT_AKA', 'name'),
        
    ]
];
