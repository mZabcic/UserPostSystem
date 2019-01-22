<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Post as Post;
use App\User as User;


class PostCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $post;
 
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }
 
    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        $user = User::where('id', '=', $this->post->user_id)->with('followers')->firstOrFail();
        $channels = [];
        foreach ($user->followers as $f) {
            array_push($channels, new PrivateChannel('user-' . $f->id ));
        }
        return $channels;
    }

    public function broadcastAs()
    {
        return 'post-created';
    }
}
