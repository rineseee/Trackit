<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class OpenAIService
{
    protected $apiKey;
    protected $apiUrl = 'https://api.openai.com/v1/chat/completions';
    protected $model;
    protected $maxTokens;
    protected $temperature;

    public function __construct()
    {
        // Read API key ONLY from .env via config()
        $this->apiKey = config('services.openai.key');

        // Read model config from .env
        $this->model = config('services.openai.model') ?? 'gpt-3.5-turbo';
        $this->maxTokens = config('services.openai.max_tokens') ?? 1000;
        $this->temperature = config('services.openai.temperature') ?? 0.7;

        // Validate API key exists
        if (!$this->apiKey) {
            Log::warning('OpenAI API key not configured. Set OPENAI_API_KEY in .env');
            throw new Exception('OpenAI API key not configured. Please set OPENAI_API_KEY in your .env file.');
        }

        // Validate API key format (should start with sk-)
        if (!$this->isValidApiKeyFormat($this->apiKey)) {
            Log::warning('Invalid OpenAI API key format');
            throw new Exception('Invalid OpenAI API key format. API key should start with "sk-"');
        }
    }

    /**
     * Validate API key format
     */
    private function isValidApiKeyFormat(string $apiKey): bool
    {
        return strlen($apiKey) > 0 && strpos($apiKey, 'sk-') === 0;
    }

    /**
     * Send a message to OpenAI and get a response
     */
    public function sendMessage(string $message, array $conversationHistory = []): string
    {
        try {
            // Validate and sanitize message
            $message = trim($message);
            if (strlen($message) === 0) {
                return 'Please provide a message.';
            }

            if (strlen($message) > 2000) {
                return 'Message is too long. Please keep it under 2000 characters.';
            }

            // Build messages array
            $messages = [];

            // Add system prompt
            $messages[] = [
                'role' => 'system',
                'content' => $this->getSystemPrompt()
            ];

            // Add conversation history (limit to last 10 messages for context)
            $historyLimit = min(count($conversationHistory), 10);
            for ($i = count($conversationHistory) - $historyLimit; $i < count($conversationHistory); $i++) {
                if ($i >= 0 && isset($conversationHistory[$i])) {
                    $messages[] = [
                        'role' => $conversationHistory[$i]['role'] ?? 'user',
                        'content' => $conversationHistory[$i]['content'] ?? ''
                    ];
                }
            }

            // Add current message
            $messages[] = [
                'role' => 'user',
                'content' => $message
            ];

            // Make API request with timeout and retry logic
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'User-Agent' => 'Trackit-ChatBot/1.0',
            ])
            ->timeout(30)
            ->retry(2, 1000)
            ->post($this->apiUrl, [
                'model' => $this->model,
                'messages' => $messages,
                'max_tokens' => $this->maxTokens,
                'temperature' => $this->temperature,
            ]);

            // Handle API errors
            if ($response->failed()) {
                $statusCode = $response->status();
                $responseData = $response->json();

                Log::error('OpenAI API Error', [
                    'status' => $statusCode,
                    'error' => $responseData['error']['message'] ?? 'Unknown error',
                    'type' => $responseData['error']['type'] ?? 'unknown_error'
                ]);

                // Provide user-friendly error messages
                if ($statusCode === 401) {
                    return 'Authentication failed. Please check your API key configuration.';
                } elseif ($statusCode === 429) {
                    return 'Rate limit exceeded. Please try again in a moment.';
                } elseif ($statusCode === 500) {
                    return 'OpenAI service is temporarily unavailable. Please try again later.';
                }

                return 'I encountered an error while processing your request. Please try again.';
            }

            $result = $response->json();

            // Validate response structure
            if (!isset($result['choices'][0]['message']['content'])) {
                Log::warning('Invalid OpenAI response structure', ['response' => $result]);
                return 'I could not process your message. Please try again.';
            }

            $assistantMessage = trim($result['choices'][0]['message']['content']);

            // Log successful interaction
            Log::info('ChatBot interaction successful', [
                'model' => $this->model,
                'input_length' => strlen($message),
                'output_length' => strlen($assistantMessage),
            ]);

            return $assistantMessage;

        } catch (Exception $e) {
            Log::error('ChatBot Error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'code' => $e->getCode(),
            ]);

            if (strpos($e->getMessage(), 'API key') !== false) {
                return 'Configuration error. Please ensure OPENAI_API_KEY is set in your .env file.';
            }

            return 'An error occurred. Please try again later.';
        }
    }

    /**
     * Get system prompt for the assistant with real application data
     */
    private function getSystemPrompt(): string
    {
        $appData = $this->getApplicationContext();

        return <<<PROMPT
You are a helpful assistant for an Issue Tracking application called "Trackit".
You have access to real application data and help users manage projects, issues, tags, and team members.

REAL APPLICATION DATA:
$appData

Your Responsibilities:
- Answer questions about projects, issues, priorities, and status using REAL DATA above
- Provide statistics and insights from actual application data
- Help users find information like "which projects are open", "what issues need attention", etc.
- Be accurate and reference the real data when answering

Guidelines:
- Be concise, professional, and helpful
- Always use the REAL DATA provided above when answering questions
- If the user asks about features, explain how to use them
- If asked about something outside your scope, politely redirect to the relevant page
- Always respond in a friendly and professional manner
- Keep responses under 500 characters when possible
- Do not provide information about API keys, authentication details, or system configuration

Never reveal:
- System architecture or backend details
- API endpoints or technical implementation
- User data or authentication mechanisms
- Database structure or queries
PROMPT;
    }

    /**
     * Get real application context data with caching
     */
    private function getApplicationContext(): string
    {
        return \Illuminate\Support\Facades\Cache::remember('ai_app_context', 300, function() {
            try {
                $projects = \App\Models\Project::select(['id', 'name', 'description'])
                    ->limit(20)
                    ->get()
                    ->map(function($p) {
                        return "- {$p->name}: " . substr($p->description ?? '', 0, 50);
                    })
                    ->implode("\n");

                $issues = \App\Models\Issue::select(['id', 'issue_number', 'title', 'status', 'priority', 'project_id'])
                    ->with('project:id,name')
                    ->orderByDesc('updated_at')
                    ->limit(10)
                    ->get()
                    ->map(function($i) {
                        return "- Issue #{$i->issue_number} ({$i->project->name}): {$i->title} [{$i->status}]";
                    })
                    ->implode("\n");

                $stats = [
                    'total_projects' => \App\Models\Project::count(),
                    'total_issues' => \App\Models\Issue::count(),
                    'open' => \App\Models\Issue::where('status', 'open')->count(),
                    'in_progress' => \App\Models\Issue::where('status', 'in_progress')->count(),
                    'closed' => \App\Models\Issue::where('status', 'closed')->count(),
                    'team' => \App\Models\User::where('is_active', true)->count(),
                ];

                return "PROJECTS ({$stats['total_projects']}):\n{$projects}\n\n" .
                       "RECENT ISSUES:\n{$issues}\n\n" .
                       "STATS:\n" .
                       "Total: {$stats['total_issues']} | " .
                       "Open: {$stats['open']} | " .
                       "In Progress: {$stats['in_progress']} | " .
                       "Closed: {$stats['closed']} | " .
                       "Team: {$stats['team']}";
            } catch (\Exception $e) {
                Log::error('Error loading AI context: ' . $e->getMessage());
                return "Application data currently unavailable.";
            }
        });
    }

    /**
     * Validate the API configuration
     */
    public function validateConfiguration(): array
    {
        $errors = [];
        $warnings = [];

        // Check API key existence
        if (!$this->apiKey) {
            $errors[] = 'OPENAI_API_KEY is not set in .env file';
        } elseif (!$this->isValidApiKeyFormat($this->apiKey)) {
            $errors[] = 'OPENAI_API_KEY has invalid format (should start with sk-)';
        }

        // Check model configuration
        if (!$this->model) {
            $warnings[] = 'Using default model: gpt-3.5-turbo';
        }

        // Check token limits
        if ($this->maxTokens < 100 || $this->maxTokens > 4000) {
            $warnings[] = "Max tokens setting ({$this->maxTokens}) outside recommended range (100-4000)";
        }

        return [
            'configured' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings,
            'model' => $this->model,
            'max_tokens' => $this->maxTokens,
            'temperature' => $this->temperature,
        ];
    }

    /**
     * Test the API connection
     */
    public function testConnection(): bool
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])
            ->timeout(10)
            ->get('https://api.openai.com/v1/models');

            if ($response->successful()) {
                Log::info('OpenAI API connection successful');
                return true;
            }

            Log::error('OpenAI API connection failed', ['status' => $response->status()]);
            return false;

        } catch (Exception $e) {
            Log::error('OpenAI API connection test error: ' . $e->getMessage());
            return false;
        }
    }
}
