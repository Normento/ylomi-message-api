<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;
use ConversationRepositoryInterface;

class ConversationController extends Controller
{

    protected $conversationRepository;

    public function __construct(ConversationRepositoryInterface $conversationRepository)
    {
        $this->conversationRepository = $conversationRepository;
    }

    /**
     * Nouvelle conversation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
        ]);

        $conversation = $this->conversationRepository->create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
        ]);

        return response()->json(['data' => $conversation], 201);
    }


    /**
     * Liste des conversations.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $user = auth()->user();

        if ($user->role == 'support') {
            $conversations = $this->conversationRepository->getSupportConversations();
        } else {
            $conversations = $this->conversationRepository->getUserConversations($user->id);
        }

        return response()->json(['data' => $conversations]);
    }


    /**
     * Afficher une conversation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = auth()->user();
        $conversation = $this->conversationRepository->findForUser($id, $user->id, $user->role);

        if (!$conversation) {
            return response()->json(['error' => 'Unauthorized or Conversation not found'], 403);
        }

        return response()->json(['data' => $conversation]);
    }

}
