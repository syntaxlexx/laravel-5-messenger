<?php

$faktory->define(['thread', 'Lexx\Messenger\Models\Thread'], function ($f) {
    $f->subject = 'Sample thread';
});

$faktory->define(['message', 'Lexx\Messenger\Models\Message'], function ($f) {
    $f->user_id = 1;
    $f->thread_id = 1;
    $f->body = 'A message';
});

$faktory->define(['participant', 'Lexx\Messenger\Models\Participant'], function ($f) {
    $f->user_id = 1;
    $f->thread_id = 1;
});
