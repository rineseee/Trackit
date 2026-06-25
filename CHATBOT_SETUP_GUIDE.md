# Trackit AI Chatbot — Setup & Configuration Guide

## ✅ Status: FULLY IMPLEMENTED & READY

Your Trackit application has a **fully functional AI chatbot** that is ready to use immediately. The chatbot includes:

- ✅ **Navbar Chat Icon** - Click the chat icon in the navbar to open the assistant
- ✅ **Floating Action Button** - Click the FAB (bottom-right) to open the assistant
- ✅ **Intelligent Responses** - Powered by OpenAI GPT models
- ✅ **Conversation History** - Maintains context across messages
- ✅ **Error Handling** - Graceful fallbacks if OpenAI is unavailable
- ✅ **Dark Mode Support** - Works perfectly in light and dark modes

---

## 🚀 Step 1: Get OpenAI API Key

### Option A: Use existing OpenAI account
1. Go to https://platform.openai.com/account/api-keys
2. Click "Create new secret key"
3. Copy the API key (starts with `sk-`)
4. Keep it safe — never share it publicly

### Option B: Create new OpenAI account
1. Go to https://platform.openai.com/signup
2. Sign up with your email
3. Verify your email
4. Add a payment method (required for API access)
5. Go to API keys section
6. Create a new secret key
7. Copy the key

---

## 🔧 Step 2: Configure Environment

### Add to `.env` file:

```env
# OpenAI Configuration
OPENAI_API_KEY=sk-your-api-key-here
OPENAI_MODEL=gpt-3.5-turbo
OPENAI_MAX_TOKENS=1000
OPENAI_TEMPERATURE=0.7
```

### Configuration Options Explained:

| Setting | Default | Description |
|---------|---------|-------------|
| `OPENAI_API_KEY` | (required) | Your OpenAI API key (starts with `sk-`) |
| `OPENAI_MODEL` | `gpt-3.5-turbo` | Model to use. Options: `gpt-3.5-turbo`, `gpt-4`, `gpt-4-turbo` |
| `OPENAI_MAX_TOKENS` | 1000 | Maximum response length (100-4000) |
| `OPENAI_TEMPERATURE` | 0.7 | Creativity level (0=focused, 1=creative) |

### Recommendations:

```env
# For fast, budget-friendly responses
OPENAI_MODEL=gpt-3.5-turbo
OPENAI_MAX_TOKENS=1000
OPENAI_TEMPERATURE=0.7

# For higher quality responses (costs more)
OPENAI_MODEL=gpt-4
OPENAI_MAX_TOKENS=2000
OPENAI_TEMPERATURE=0.5
```

---

## ✅ Step 3: Verify Configuration

### Check if chatbot is configured:

```bash
php artisan tinker
```

Then run:
```php
$service = new \App\Services\OpenAIService();
$validation = $service->validateConfiguration();
dd($validation);
```

Expected output:
```
[
  'configured' => true,
  'errors' => [],
  'warnings' => [],
  'model' => 'gpt-3.5-turbo',
  'max_tokens' => 1000,
  'temperature' => 0.7,
]
```

If `configured` is `false`, check the `errors` array for details.

---

## 🧪 Step 4: Test the Chatbot

### Test in Application:

1. **Start your development server:**
   ```bash
   php artisan serve
   ```

2. **Open http://localhost:8000/dashboard**

3. **Open the chatbot:**
   - Click the chat icon in the navbar (top-right)
   - OR click the floating button (bottom-right)

4. **Send a test message:**
   - Type: "Hello! What can you help me with?"
   - You should get a response within 2-3 seconds

5. **Test more complex questions:**
   - "How do I create a new project?"
   - "What are the best practices for issue tracking?"
   - "How do I manage my team?"

---

## 🔍 Common Issues & Solutions

### Issue: "AI Assistant is not configured"

**Cause:** `OPENAI_API_KEY` is not set in `.env`

**Solution:**
1. Open `.env` file
2. Add: `OPENAI_API_KEY=sk-your-key-here`
3. Save the file
4. Restart your development server
5. Try again

### Issue: "Authentication failed"

**Cause:** API key is invalid or expired

**Solution:**
1. Go to https://platform.openai.com/account/api-keys
2. Delete the old key
3. Create a new key
4. Update `.env` with the new key
5. Restart the server

### Issue: "Rate limit exceeded"

**Cause:** Too many API requests in a short time

**Solution:**
- This is normal if you're testing heavily
- Wait a few moments and try again
- Upgrade your OpenAI plan for higher limits

### Issue: "Service is temporarily unavailable"

**Cause:** OpenAI servers are down

**Solution:**
- Check https://status.openai.com/
- Wait and try again
- The application will continue to work, just without AI

### Issue: No response appears

**Cause:** Network issue or slow connection

**Solution:**
1. Check browser console (F12) for errors
2. Verify API key is correct in `.env`
3. Test with: `curl https://api.openai.com/v1/models -H "Authorization: Bearer sk-your-key"`
4. Check Laravel logs: `storage/logs/laravel.log`

---

## 💰 Pricing & Cost Management

### OpenAI API Pricing:

- **gpt-3.5-turbo**: ~$0.0005 per message
- **gpt-4**: ~$0.03-$0.06 per message
- **gpt-4-turbo**: ~$0.01-$0.03 per message

### Cost Control Tips:

1. **Set usage limits in OpenAI dashboard:**
   - Go to https://platform.openai.com/account/billing/limits
   - Set monthly budget limit
   - Set rate limit (requests per minute)

2. **Reduce max tokens:**
   ```env
   OPENAI_MAX_TOKENS=500  # Shorter responses = lower cost
   ```

3. **Use gpt-3.5-turbo** for better value:
   ```env
   OPENAI_MODEL=gpt-3.5-turbo  # ~10x cheaper than gpt-4
   ```

4. **Monitor usage:**
   - Check OpenAI dashboard regularly
   - View usage at: https://platform.openai.com/account/billing/overview

---

## 🎯 Advanced Configuration

### Customize the System Prompt

To change how the AI responds, edit `app/Services/OpenAIService.php`, line 166:

```php
private function getSystemPrompt(): string
{
    return <<<'PROMPT'
You are a helpful assistant for Trackit issue tracking system.
[Customize this text to change AI behavior]
PROMPT;
}
```

Example customizations:

```php
// More technical responses
"You are a technical expert in project management and agile workflows..."

// More casual tone
"You are a friendly assistant that helps teams manage their work..."

// Multiple languages
"You are a multilingual assistant that can respond in any language the user requests..."
```

### Enable Production Features

For production deployment, add to your `.env`:

```env
# Use production-grade GPT-4
OPENAI_MODEL=gpt-4

# Higher token limit for detailed responses
OPENAI_MAX_TOKENS=2000

# More focused responses
OPENAI_TEMPERATURE=0.5

# Enable conversation context (set to 1 to track user sessions)
# Note: Currently supports last 10 messages per session
```

---

## 📊 Monitoring & Logging

### View Chatbot Interactions

Check logs in `storage/logs/laravel.log`:

```bash
tail -f storage/logs/laravel.log | grep ChatBot
```

You'll see entries like:
```
[2026-06-25 12:34:56] local.INFO: ChatBot interaction successful
{"model":"gpt-3.5-turbo","input_length":45,"output_length":156}
```

### Track API Errors

Errors are logged automatically:
```
[2026-06-25 12:35:00] local.ERROR: OpenAI API Error
{"status":429,"error":"Rate limit exceeded","type":"rate_limit_error"}
```

---

## 🚀 Deployment Checklist

When deploying to production:

- [ ] `OPENAI_API_KEY` is set in production `.env`
- [ ] API key is **not** exposed in code or git history
- [ ] Usage limits are set in OpenAI dashboard
- [ ] Monitoring is enabled (e.g., Sentry, New Relic)
- [ ] Error emails are configured
- [ ] Logs are being collected

---

## ✨ Features Overview

### What the Chatbot Can Help With:

1. **Project Management**
   - How to create projects
   - Best practices for organizing work
   - How to set deadlines

2. **Issue Tracking**
   - Creating and updating issues
   - Assigning work to team members
   - Priority and status management

3. **Team Collaboration**
   - Managing team members
   - Assigning permissions
   - Communication tips

4. **Usage & Best Practices**
   - Getting started guide
   - Tips and tricks
   - Troubleshooting

### What the Chatbot Cannot Do:

- ❌ Access real user data (privacy-first design)
- ❌ Modify projects or issues directly
- ❌ See authentication details
- ❌ Access system configuration
- ❌ Provide financial advice
- ❌ Make decisions for you

---

## 🔐 Security Considerations

### API Key Safety:

✅ **DO:**
- Store API key in `.env` file (never in code)
- Regenerate keys regularly
- Use environment variables in production
- Monitor API usage
- Rotate keys if compromised

❌ **DON'T:**
- Hardcode API keys in code
- Commit `.env` to git
- Share API keys
- Use the same key across multiple environments
- Log sensitive API information

### User Privacy:

- Messages are sent to OpenAI's servers
- OpenAI processes requests according to their privacy policy
- Chat history is stored locally in browser
- User data is never exposed to the AI
- Admin cannot see user conversations

---

## 📞 Support & Resources

### Need Help?

1. **Check the logs:**
   ```bash
   tail -20 storage/logs/laravel.log
   ```

2. **Verify configuration:**
   ```bash
   php artisan tinker
   $service = new \App\Services\OpenAIService();
   $service->testConnection();
   ```

3. **Review OpenAI docs:**
   - https://platform.openai.com/docs
   - https://help.openai.com

4. **Check status:**
   - https://status.openai.com/

---

## 🎉 You're All Set!

Your Trackit AI Chatbot is ready to use! 

### Next Steps:

1. ✅ Add `OPENAI_API_KEY` to your `.env` file
2. ✅ Restart your development server
3. ✅ Open http://localhost:8000/dashboard
4. ✅ Click the chat icon to start chatting
5. ✅ Deploy to production when ready

---

**Happy chatting! 🤖💬**

*Last updated: June 25, 2026*
*Chatbot Status: ✅ Fully Implemented & Ready*
