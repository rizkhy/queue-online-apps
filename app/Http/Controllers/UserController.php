<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getQueueData()
    {
        $queues = Queue::all();

        return view('admin.user.user', compact('queues'));
    }
}
