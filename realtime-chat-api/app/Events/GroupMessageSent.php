<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupMessageSent implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $message;

    public $groupId;

    public function __construct($message, $groupId)
    {
        $this->message = [

            'id' => $message->id,

            'sender_id' => $message->sender_id,

            'group_id' => $message->group_id,

            'message' => $message->message,

            'sender_name' => $message->sender->name,

            'avatar_url' => $message->sender->avatar_url,

            'created_at' => $message->created_at,
        ];

        $this->groupId = $groupId;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('group.' . $this->groupId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'group.message.sent';
    }
}
