<?php

namespace App\Http\Controllers;

use App\Services\OpenAIService;
use Illuminate\Http\Request;

class ChatBotController extends Controller
{
    protected $openAI;
    protected $configError = null;

    public function __construct()
    {
        try {
            $this->openAI = new OpenAIService();
        } catch (\Exception $e) {
            // Store the error for later use
            $this->configError = $e->getMessage();
            \Log::warning('OpenAI Service initialization failed: ' . $e->getMessage());
        }
    }

    /**
     * Send message to OpenAI and get response
     */
    public function sendMessage(Request $request)
    {
        // Check if OpenAI service is properly configured
        if (!$this->openAI) {
            return response()->json([
                'success' => false,
                'error' => 'AI Assistant is not configured. Please set OPENAI_API_KEY in .env file.',
            ], 503);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:2000|min:1',
            'history' => 'nullable|array',
        ]);

        try {
            $message = $validated['message'];
            $history = $validated['history'] ?? [];

            // Get response from OpenAI
            $response = $this->openAI->sendMessage($message, $history);

            return response()->json([
                'success' => true,
                'response' => $response,
                'timestamp' => now()->toIso8601String(),
            ]);

        } catch (\Exception $e) {
            \Log::error('ChatBot Error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'code' => $e->getCode(),
            ]);

            // Determine if it's a configuration error or temporary error
            $statusCode = strpos($e->getMessage(), 'API key') !== false ? 503 : 500;
            $errorMessage = strpos($e->getMessage(), 'API key') !== false
                ? 'Configuration error. Please ensure OPENAI_API_KEY is properly set.'
                : 'Failed to get response from AI assistant. Please try again.';

            return response()->json([
                'success' => false,
                'error' => $errorMessage,
            ], $statusCode);
        }
    }

    /**
     * Clear conversation history
     */
    public function clearHistory()
    {
        return response()->json([
            'success' => true,
            'message' => 'Conversation history cleared',
        ]);
    }
}
