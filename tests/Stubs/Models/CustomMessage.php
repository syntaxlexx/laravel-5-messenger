<?php

namespace Lexx\Messenger\Test\Stubs\Models;

use Lexx\Messenger\Models\Message;

class CustomMessage extends Message
{
    protected $table = 'custom_messages';
}
