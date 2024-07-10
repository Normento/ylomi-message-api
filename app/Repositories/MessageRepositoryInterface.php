<?php

use App\Models\Conversation;

interface MessageRepositoryInterface
{
    public function create(array $data);

    public function getConversationMessages($conversationId);

    public function find($id);

    public function findForUser($id, $userId, $role);

    public function createForConversation($conversationId, $userId, $messageContent);


}