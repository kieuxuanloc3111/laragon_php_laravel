<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $message;

    public $userId;

    public function __construct($message, $userId)
    {
        $this->message = [

            'id' => $message->id,

            'sender_id' => $message->sender_id,

            'receiver_id' => $message->receiver_id,

            'message' => $message->message,

            'avatar_url' =>
                $message->sender->avatar_url,
        ];

        $this->userId = $userId;
    }

    public function broadcastOn(): array
    {
        return [

            new PrivateChannel(
                'chat.' . $this->userId
            )
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }
}
