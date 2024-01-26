<?php

namespace App\Http\Controllers;

use App\Events\QueueUpdated;
use App\Models\Queue;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function login()
    {
        return view('admin.login.login');
    }

    public function doLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            return redirect()->route('antrians');
        }

        return redirect()->back()->withErrors(['email' => 'Invalid email or password']);
    }

    public function getAntrian()
    {
        $currentAntrian = Queue::where('status', 'sedang dilayani')->orderBy('queue_number', 'desc')->first();

        $finishedAntrians = Queue::where('status', 'selesai')->get();

        $antrians = Queue::where('status', 'menunggu')->orderBy('id', 'asc')->get();

        return view('admin.queue.queue', compact('currentAntrian', 'finishedAntrians', 'antrians'));
    }

    public function listAntrian()
    {
        $listAntrians = Queue::where('status', 'menunggu')->get();

        return response()->json($listAntrians);
    }

    public function getFinishedQueues()
    {
        $finishedAntrians = Queue::where('status', 'selesai')->get();

        return response()->json($finishedAntrians);
    }

    public function navigateAntrian(Request $request)
    {
        $direction = $request->input('direction'); // 

        $currentAntrian = Queue::where('queue_number', $request->input('current_queue_number'))
            ->where('status', 'sedang dilayani')
            ->first();

        if (!$currentAntrian) {
            $currentAntrian = Queue::where('status', 'menunggu')->orderBy('id')->first();
        }

        $nextAntrian = ($direction === 'next')
            ? Queue::where('id', '>', $currentAntrian->id)->where('status', 'menunggu')->orderBy('id')->first()
            : Queue::where('id', '<', $currentAntrian->id)->where('status', 'menunggu')->orderBy('id', 'desc')->first();

        if (!$nextAntrian) {
            return response()->json(['message' => 'No more queues in the specified direction']);
        }

        $currentAntrian->status = 'selesai';
        $currentAntrian->save();

        $nextAntrian->status = 'sedang dilayani';
        $nextAntrian->save();


        broadcast(new QueueUpdated($nextAntrian))->toOthers();

        return response()->json([
            'success' => true,
            'message' => 'Navigated to the next queue',
        ]);
    }
}
