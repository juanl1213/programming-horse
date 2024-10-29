<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Client;

class OpenAIController extends Controller
{
    protected $openai;

    public function __construct()
    {
        $this->openai = new Client([
            'organization' => '', 
            'api_key' => '',
        ]);
    }

    public function generateChatCompletion(Request $request)
    {
        $userMessage = $request->input('message');

        try {
            $completion = $this->openai->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => $userMessage],
                ],
            ]);

            return response()->json([
                'response' => $completion['choices'][0]['message']['content'],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
