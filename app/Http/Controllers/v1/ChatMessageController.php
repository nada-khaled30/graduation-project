<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatMessageController extends Controller
{
    public function index(Request $request)
    {
        $messages = ChatMessage::where('user_id', $request->user()->id)
                                ->orderBy('created_at', 'asc')
                                ->get();

        return response()->json([
            'success' => true,
            'message' => 'Messages retrieved successfully.',
            'data'    => $messages,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $userMessage = $request->message;
        $botReply = 'لم يتم الرد من المساعد.';

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
                'Content-Type'  => 'application/json',
            ])->timeout(60)->post('https://openrouter.ai/api/v1/chat/completions', [
                'model'    => 'deepseek/deepseek-r1:free',
                'messages' => [
                    ['role' => 'system', 'content' => 'أنت مساعد ذكي في طب الأسنان...'],
                    ['role' => 'user',   'content' => $userMessage],
                ],
            ]);

            if ($response->successful() && isset($response['choices'][0]['message']['content'])) {
                $botReply = $response['choices'][0]['message']['content'];
            } else {
                Log::warning('OpenRouter returned invalid response', ['response' => $response->body()]);
            }
        } catch (\Exception $e) {
            Log::error('Error communicating with OpenRouter API', ['error' => $e->getMessage()]);
        }

        $chat = ChatMessage::create([
            'user_id' => $request->user()->id,
            'message' => $userMessage,
            'reply'   => $botReply,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message stored successfully.',
            'data'    => $chat,
        ], 201);
    }
}
