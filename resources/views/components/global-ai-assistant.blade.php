<div class="trackit-ai-backdrop" id="globalAiBackdrop" hidden></div>

<aside class="trackit-ai-drawer" id="globalAiDrawer" aria-label="AI assistant" aria-hidden="true"
    data-send-url="{{ route('chatbot.sendMessage') }}" data-clear-url="{{ route('chatbot.clearHistory') }}">
    <div class="trackit-ai-header">
        <div>
            <div class="trackit-ai-eyebrow">Trackit AI</div>
            <h2>Assistant</h2>
        </div>
        <button type="button" class="trackit-icon-button inverse" id="globalAiClose" aria-label="Close AI assistant">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <div class="trackit-ai-messages" id="globalAiMessages" aria-live="polite">
        <div class="trackit-ai-message assistant">
            <div class="trackit-ai-bubble">
                Hello. Ask me about projects, issues, tags, team workflow, or how to use Trackit.
            </div>
        </div>
    </div>

    <form class="trackit-ai-form" id="globalAiForm">
        <label class="visually-hidden" for="globalAiInput">Message</label>
        <input type="text" id="globalAiInput" name="message" maxlength="2000" autocomplete="off"
            placeholder="Ask the assistant..." required>
        <button type="submit" class="trackit-ai-send" aria-label="Send message">
            <i class="bi bi-send"></i>
        </button>
    </form>
</aside>