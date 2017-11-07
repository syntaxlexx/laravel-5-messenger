<?php

namespace Lexx\Messenger\Test\Stubs\Models;

use Lexx\Messenger\Models\Participant;

class CustomParticipant extends Participant
{
    protected $table = 'custom_participants';
}
