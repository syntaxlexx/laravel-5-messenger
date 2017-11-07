<?php

namespace Lexx\ChatMessenger\Test\Stubs\Models;

use Lexx\ChatMessenger\Models\Message;

class CustomMessage extends Message
{
    protected $table = 'custom_messages';
}
