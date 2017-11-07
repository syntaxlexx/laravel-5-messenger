<?php

return [

    'user_model' => App\User::class,

    'message_model' => Lexx\Messenger\Models\Message::class,

    'participant_model' => Lexx\Messenger\Models\Participant::class,

    'thread_model' => Lexx\Messenger\Models\Thread::class,

    /**
     * Define custom database table names - without prefixes.
     */
    'messages_table' => null,

    'participants_table' => null,

    'threads_table' => null,

    /**
     * Define custom database table names - without prefixes.
    */

    'use_pusher' => false,
];
