<?php

use App\Models\Conversation;

interface ConversationRepositoryInterface
{
    public function create(array $data);

    public function getUserConversations($userId);

    public function getSupportConversations();

    public function find($id);


    public function findForUser($id, $userId, $role);


}