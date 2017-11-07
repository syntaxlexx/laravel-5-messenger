<?php

namespace Lexx\Messenger\Test\Stubs\Models;

use Lexx\Messenger\Models\Thread;

class CustomThread extends Thread
{
    protected $table = 'custom_threads';
}
