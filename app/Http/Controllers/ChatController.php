<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Muestra el chat de una oferta específica
     */
    public function show($bidId)
    {
        $bid = Bid::with(['user', 'bideable.user'])->findOrFail($bidId);
        $currentUserId = Auth::id();
        
        // Buscar o crear el chat
        $chat = Chat::firstOrCreate(
            ['bid_id' => $bidId],
            [
                'user_a_id' => $bid->user_id,
                'user_b_id' => $bid->bideable->user_id
            ]
        );

        // Marcar mensajes como leídos
        if ($chat) {
            $chat->markAsRead($currentUserId);
        }

        // Cargar mensajes con información del usuario
        $messages = $chat->messages()->with('user')->get();
        
        return view('chats.show', [
            'chat' => $chat,
            'bid' => $bid,
            'messages' => $messages,
            'otherUser' => $chat->getOtherParticipant($currentUserId)
        ]);
    }

    /**
     * Envía un mensaje en el chat
     */
    public function sendMessage(Request $request, Chat $chat)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $message = $chat->messages()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'read' => false
        ]);

        // Cargar la relación de usuario para la respuesta
        $message->load('user');

        // Aquí podrías agregar notificaciones en tiempo real con Laravel Echo
        // broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => $message
        ]);
    }

    /**
     * Obtiene los mensajes de un chat
     */
    public function getMessages(Chat $chat)
    {
        $messages = $chat->messages()->with('user')->get();
        
        // Marcar mensajes como leídos
        $chat->markAsRead(Auth::id());

        return response()->json([
            'status' => 'success',
            'messages' => $messages
        ]);
    }

    /**
     * Obtiene la lista de chats del usuario autenticado
     */
    public function index()
    {
        $userId = Auth::id();
        
        $chats = Chat::where('user_a_id', $userId)
            ->orWhere('user_b_id', $userId)
            ->with(['bid', 'messages' => function($query) {
                $query->latest()->first();
            }])
            ->get()
            ->map(function($chat) use ($userId) {
                $chat->unread_count = $chat->unreadCount($userId);
                $chat->other_user = $chat->getOtherParticipant($userId);
                return $chat;
            });

        return view('chats.index', compact('chats'));
    }

    /**
     * Obtiene el historial de mensajes de un chat
     */
    public function getChatHistory(Chat $chat)
    {
        $messages = $chat->messages()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'status' => 'success',
            'messages' => $messages->reverse()->values(),
            'hasMore' => $messages->hasMorePages()
        ]);
    }

    /**
     * Marca todos los mensajes de un chat como leídos
     */
    public function markAsRead(Chat $chat)
    {
        $chat->markAsRead(Auth::id());
        
        return response()->json([
            'status' => 'success',
            'unread_count' => 0
        ]);
    }
}
