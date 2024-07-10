<?php

namespace App\Repositories;

use App\Models\Message;
use App\Models\Conversation;

class MessageRepository implements MessageRepositoryInterface

{

    public function create(array $data)
    {
        return Message::create($data);
    }

    public function getConversationMessages($conversationId)
    {
        return Message::where('conversation_id', $conversationId)
            ->with('user')
            ->get();
    }

    public function find($id)
    {
        return Message::find($id);
    }

    public function findForUser($id, $userId, $role)
    {
        $message = Message::find($id);

        if ($message) {
            $conversation = $message->conversation;

            if ($conversation && ($conversation->sender_id == $userId || $conversation->receiver_id == $userId || $role == 'support')) {
                return $message;
            }
        }

        return null;
    }

    public function createForConversation($conversationId, $userId, $messageContent)
    {
        $conversation = Conversation::find($conversationId);

        if (!$conversation) {
            return null;
        }

        if ($conversation->sender_id != $userId && $conversation->receiver_id != $userId && $userId != 'support') {
            return null;
        }

        return $conversation->messages()->create([
            'user_id' => $userId,
            'message' => $messageContent,
        ]);
    }
}