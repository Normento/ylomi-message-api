<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\NewMessage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewMessageNotification;

class NewMessageListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(NewMessage $event): void
    {

        $message = $event->message;
        $user = $message->user;

        // Détermination  du destinataire
        if (in_array($user->role, ['admin', 'support'])) {

            $recipient = User::find($message->conversation->client_id);
        } else {
            
            $recipient = User::whereIn('role', ['admin', 'support'])->get();
        }

        // Envoyer la notification
        Notification::send($recipient, new NewMessageNotification($message));
    }
    
}

