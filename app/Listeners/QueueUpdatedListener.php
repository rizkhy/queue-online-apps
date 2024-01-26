<?php

namespace App\Listeners;

use App\Events\QueueUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class QueueUpdatedListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(QueueUpdated $event)
    {
        $updatedQueue = $event->updatedQueue;

        Log::info("UserQueueUpdatedListener handling event", [
            'queue_id' => $updatedQueue->id,
            'queue_number' => $updatedQueue->queue_number,
        ]);

        $this->updateQueueList($updatedQueue);
    }

    private function updateQueueList($updatedQueue)
    {
        //
    }
}
