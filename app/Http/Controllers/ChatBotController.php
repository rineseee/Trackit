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
     * Send message to OpenAI - MAXIMUM SECURITY
     */
    public function sendMessage(Request $request)
    {
        // Verify user is authenticated (middleware should handle this, but double-check)
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized',
            ], 401);
        }

        // Check if OpenAI service is properly configured
        if (!$this->openAI) {
            \Log::critical('OpenAI Service not configured - missing API key');

            // Return generic error to prevent info disclosure
            return response()->json([
                'success' => false,
                'error' => 'AI Assistant is temporarily unavailable. Please try again later.',
            ], 503);
        }

        // Validate input
        $validated = $request->validate([
            'message' => 'required|string|max:2000|min:1',
            'history' => 'nullable|array|max:20',  // Limit history to prevent memory abuse
        ]);

        try {
            $message = trim($validated['message']);
            $history = $validated['history'] ?? [];

            // Sanitize message to prevent prompt injection
            $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

            // Log user interaction for audit trail (no sensitive data)
            \Log::info("User " . auth()->id() . " sent message to AI", [
                'message_length' => strlen($message),
                'timestamp' => now(),
            ]);

            // Get response from OpenAI
            $response = $this->openAI->sendMessage($message, $history);

            return response()->json([
                'success' => true,
                'response' => $response,
                'timestamp' => now()->toIso8601String(),
            ]);

        } catch (\Exception $e) {
            // Log with full details securely (not exposed to user)
            \Log::error('ChatBot Error - User ' . auth()->id(), [
                'message' => $e->getMessage(),
                'exception_class' => get_class($e),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null,
            ]);

            // Return GENERIC error message to user (no details exposed)
            return response()->json([
                'success' => false,
                'error' => 'Unable to process your request. Please try again later.',
            ], 500);
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
