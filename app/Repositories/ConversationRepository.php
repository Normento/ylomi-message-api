<?php

namespace App\Repositories;

use App\Models\Conversation;

class ConversationRepository implements ConversationRepositoryInterface

{
    public function create(array $data)
    {
        return Conversation::create($data);
    }

    public function getUserConversations($userId)
    {
        return Conversation::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver', 'messages'])
            ->get();
    }

    public function getSupportConversations()
    {
        return Conversation::with(['sender', 'receiver', 'messages'])
            ->get();
    }


    public function find($id)
    {
        return Conversation::find($id);
    }

    public function findForUser($id, $userId, $role)
    {
        $conversation = Conversation::find($id);

        if ($conversation && ($conversation->sender_id == $userId || $conversation->receiver_id == $userId || $role == 'support')) {
            return $conversation;
        }

        return null;
    }

    
}