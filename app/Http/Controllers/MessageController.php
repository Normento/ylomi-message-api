<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Events\NewMessage;
use App\Models\Conversation;
use Illuminate\Http\Request;
use MessageRepositoryInterface;

class MessageController extends Controller
{

    protected $messageRepository;

    public function __construct(MessageRepositoryInterface $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    /**
     * Envoyer un nouveau message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function store(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message' => 'required|string',
        ]);

        $user = auth()->user();
        $message = $this->messageRepository->createForConversation(
            $request->conversation_id,
            $user->id,
            $request->message
        );

        if (!$message) {
            return response()->json(['error' => 'Unauthorized or Conversation not found'], 403);
        }

        // Diffuser l'événement NewMessage
        broadcast(new NewMessage($message))->toOthers();

        return response()->json(['data' => $message], 201);
    
    }
    

    /**
     * Afficher la liste des message dans une conversation.
     *
     * @param  int  $conversationId
     * @return \Illuminate\Http\Response
     */


    public function index($conversationId)
    {
        $conversation = $this->conversationRepository->find($conversationId);
        $user = auth()->user();

        // Vérifier que l'utilisateur fait partie de la conversation ou qu'il est support
        if ($conversation->sender_id != $user->id && $conversation->receiver_id != $user->id && $user->role != 'support') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = $this->messageRepository->getConversationMessages($conversationId);

        return response()->json(['data' => $messages]);
    }
}
