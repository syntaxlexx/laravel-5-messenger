<?php

namespace Lexx\ChatMessenger\Test\Stubs\Models;

use Lexx\ChatMessenger\Models\Thread;

class CustomThread extends Thread
{
    protected $table = 'custom_threads';
}
