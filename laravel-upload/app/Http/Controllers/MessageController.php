<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Events\MessageSent; // <--- TRÈS IMPORTANT : ne pas l'oublier
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())
            ->with(['messagesSent' => function($q) {
                $q->where('receiver_id', auth()->id())->latest();
            }, 'messagesReceived' => function($q) {
                $q->where('sender_id', auth()->id())->latest();
            }])
            ->get()
            ->map(function($user) {
                $lastSent = $user->messagesReceived->first();
                $lastReceived = $user->messagesSent->first();

                $user->last_message = collect([$lastSent, $lastReceived])
                    ->filter() // On enlève les valeurs nulles
                    ->sortByDesc('created_at')
                    ->first();
                return $user;
            })
            ->sortByDesc(fn($u) => optional($u->last_message)->created_at);

        return view('messages.index', compact('users'));
    }

    public function show(User $user)
    {
        $messages = Message::where(function($q) use ($user) {
            $q->where('sender_id', auth()->id())->where('receiver_id', $user->id);
        })->orWhere(function($q) use ($user) {
            $q->where('sender_id', $user->id)->where('receiver_id', auth()->id());
        })->orderBy('created_at', 'asc')->get();

        return view('messages.show', compact('user', 'messages'));
    }

    public function store(Request $request, User $user)
    {
        $request->validate([
            'content' => 'required|string|max:5000',
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $user->id,
            'content' => $request->content,
        ]);

        auth()->user()->increment('xp', 10);

        // Broadcast via Reverb (ne bloque pas si non configuré)
        try {
            broadcast(new MessageSent($message))->toOthers();
        } catch (\Exception $e) {
            // Broadcasting non configuré, on continue quand même
            \Illuminate\Support\Facades\Log::debug('Broadcasting non disponible: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
}
