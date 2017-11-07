<?php

namespace Lexx\ChatMessenger\Test\Stubs\Models;

use Lexx\ChatMessenger\Models\Participant;

class CustomParticipant extends Participant
{
    protected $table = 'custom_participants';
}
