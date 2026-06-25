<?php
/**
 * Trackit Chatbot Diagnostic Tool
 * Run this to identify chatbot issues
 */

echo "═══════════════════════════════════════════════════════════\n";
echo "TRACKIT CHATBOT DIAGNOSTIC TEST\n";
echo "═══════════════════════════════════════════════════════════\n\n";

// 1. Check if file exists
echo "1. CHECKING FILE STRUCTURE...\n";
$files = [
    'app/Http/Controllers/ChatBotController.php',
    'app/Services/OpenAIService.php',
    'resources/views/components/global-ai-assistant.blade.php',
    'config/services.php',
];

foreach ($files as $file) {
    $path = base_path($file);
    $exists = file_exists($path) ? '✅ EXISTS' : '❌ MISSING';
    echo "   $file: $exists\n";
}

echo "\n2. CHECKING ENVIRONMENT CONFIGURATION...\n";
$apiKey = env('OPENAI_API_KEY');
if ($apiKey) {
    $masked = substr($apiKey, 0, 7) . '...' . substr($apiKey, -7);
    echo "   OPENAI_API_KEY: ✅ SET ($masked)\n";
} else {
    echo "   OPENAI_API_KEY: ❌ NOT SET\n";
}

echo "   OPENAI_MODEL: " . (env('OPENAI_MODEL') ? env('OPENAI_MODEL') : 'gpt-3.5-turbo (default)') . "\n";
echo "   OPENAI_MAX_TOKENS: " . (env('OPENAI_MAX_TOKENS') ? env('OPENAI_MAX_TOKENS') : '1000 (default)') . "\n";

echo "\n3. CHECKING ROUTES...\n";
$routes = [
    'chatbot.sendMessage' => route('chatbot.sendMessage'),
    'chatbot.clearHistory' => route('chatbot.clearHistory'),
];

foreach ($routes as $name => $url) {
    echo "   $name: ✅ $url\n";
}

echo "\n4. CHECKING OPENAI SERVICE...\n";
try {
    $service = new \App\Services\OpenAIService();
    $validation = $service->validateConfiguration();

    if ($validation['configured']) {
        echo "   Service Status: ✅ CONFIGURED\n";
        echo "   Model: " . $validation['model'] . "\n";
        echo "   Max Tokens: " . $validation['max_tokens'] . "\n";
        echo "   Temperature: " . $validation['temperature'] . "\n";

        echo "\n5. TESTING API CONNECTION...\n";
        $connection = $service->testConnection();
        if ($connection) {
            echo "   OpenAI Connection: ✅ SUCCESS\n";
        } else {
            echo "   OpenAI Connection: ❌ FAILED\n";
            echo "   → Check API key validity at https://platform.openai.com/account/api-keys\n";
        }
    } else {
        echo "   Service Status: ❌ NOT CONFIGURED\n";
        echo "   Errors:\n";
        foreach ($validation['errors'] as $error) {
            echo "      • $error\n";
        }
    }
} catch (\Exception $e) {
    echo "   Service Error: ❌ " . $e->getMessage() . "\n";
}

echo "\n6. CHECKING CONTROLLER...\n";
try {
    $controller = new \App\Http\Controllers\ChatBotController();
    echo "   ChatBotController: ✅ LOADED\n";
} catch (\Exception $e) {
    echo "   ChatBotController: ❌ ERROR - " . $e->getMessage() . "\n";
}

echo "\n═══════════════════════════════════════════════════════════\n";
echo "SUMMARY\n";
echo "═══════════════════════════════════════════════════════════\n\n";

if ($apiKey) {
    echo "✅ Configuration looks good!\n\n";
    echo "NEXT STEPS:\n";
    echo "1. Open http://localhost:8000/dashboard in your browser\n";
    echo "2. Open Developer Tools (F12)\n";
    echo "3. Go to Console tab\n";
    echo "4. Click the chat icon to open the chatbot\n";
    echo "5. Type a test message\n";
    echo "6. Check Console for any error messages\n";
    echo "7. Check Network tab to see API calls\n";
} else {
    echo "❌ OPENAI_API_KEY is missing!\n\n";
    echo "TO FIX:\n";
    echo "1. Go to https://platform.openai.com/account/api-keys\n";
    echo "2. Create a new API key\n";
    echo "3. Add to .env file:\n";
    echo "   OPENAI_API_KEY=sk-your-api-key-here\n";
    echo "4. Restart your development server\n";
    echo "5. Run this diagnostic again\n";
}

echo "\n═══════════════════════════════════════════════════════════\n";
echo "If issues persist, share the output above for support.\n";
echo "═══════════════════════════════════════════════════════════\n";
