# 🚀 CHATBOT - QUICK REFERENCE CARD

**Print this or bookmark it!**

---

## ⚡ SETUP (2 MINUTES)

### Step 1: Get Key
```
Visit: https://platform.openai.com/api-keys
Create: New secret key
Copy: The key
```

### Step 2: Update .env
```env
OPENAI_API_KEY=sk-your-key-here
```

### Step 3: Use It
```
http://127.0.0.1:8000/chatbot
```

---

## 📋 URLS

| Purpose | URL |
|---------|-----|
| ChatBot | http://127.0.0.1:8000/chatbot |
| API Keys | https://platform.openai.com/api-keys |
| Usage | https://platform.openai.com/account/usage |

---

## ⚙️ CONFIGURATION

### Required
```env
OPENAI_API_KEY=sk-xxx
```

### Optional (Defaults OK)
```env
OPENAI_MODEL=gpt-3.5-turbo
OPENAI_MAX_TOKENS=1000
OPENAI_TEMPERATURE=0.7
```

---

## 🔐 SECURITY

✅ Keys in .env only
✅ Never hardcode keys
✅ Input validated
✅ Errors logged
✅ CSRF protected

---

## 🧪 TESTING

```bash
# Visit chatbot
http://127.0.0.1:8000/chatbot

# Send test message
"How do I create a project?"

# Should see AI response
# If error, check .env file
```

---

## 🚨 ERROR: "Not Configured"

**Problem**: Error alert on /chatbot

**Solution**:
1. Check .env has OPENAI_API_KEY
2. Verify key starts with `sk-`
3. Restart Laravel server
4. Refresh page

---

## 🚨 ERROR: "API Key Invalid"

**Problem**: "API key invalid" message

**Solution**:
1. Get new key from OpenAI
2. Update .env file
3. Restart server
4. Try again

---

## 🚨 ERROR: Rate Limited

**Problem**: "Rate limit exceeded"

**Solution**:
1. Wait 1 minute
2. Try again
3. Check OpenAI usage
4. Consider upgrading plan

---

## 📱 RESPONSIVE

| Device | Works |
|--------|-------|
| Desktop | ✅ Yes |
| Tablet | ✅ Yes |
| Mobile | ✅ Yes |

---

## 📊 FILES

### Modified
- `app/Services/OpenAIService.php`
- `app/Http/Controllers/ChatBotController.php`
- `config/services.php`
- `resources/views/chatbot/index.blade.php`

### Documentation
- `CHATBOT_QUICK_START.md` ← Start here
- `CHATBOT_SECURITY.md` ← Security
- `CHATBOT_SETUP.md` ← Complete guide
- `CHATBOT_FINAL_CHECKLIST.md` ← Checklist

---

## 💰 COSTS

```
~$0.01 per conversation
~$0.30 per user per day
~$10 per 100 users per month
```

Use `gpt-3.5-turbo` for budget-friendly

---

## ✨ FEATURES

✅ Chat interface
✅ Message history
✅ Quick commands
✅ Clear button
✅ Error handling
✅ Mobile responsive
✅ Configuration alerts
✅ Sidebar integration

---

## 🔧 TIPS

### Faster Responses
- Lower max_tokens
- Use gpt-3.5-turbo
- Clear old history

### Better Answers
- Longer max_tokens
- Higher temperature
- More context

### Cost Saving
- Use gpt-3.5-turbo
- Lower max_tokens
- Shorter responses

---

## 📞 DOCUMENTATION

| File | Purpose | Time |
|------|---------|------|
| QUICK_START | Setup | 5 min |
| SECURITY | Security | 15 min |
| SETUP | Complete | 20 min |
| CHECKLIST | Deployment | 15 min |

---

## ✅ CHECKLIST

- [ ] Get API key
- [ ] Add to .env
- [ ] Restart server
- [ ] Visit /chatbot
- [ ] No error alert?
- [ ] Send test message
- [ ] Get response?
- [ ] Clear works?

---

## 🆘 QUICK HELP

**Can't send message?**
- Check for error alert
- Check API key in .env
- Check internet connection
- Check browser console

**Getting timeout?**
- API is slow (normal)
- Check internet
- Wait and retry
- Check OpenAI status

**Wrong responses?**
- Clear history
- Be more specific
- Try simpler questions
- Check model choice

---

## 🌐 EXTERNAL LINKS

- OpenAI API: https://platform.openai.com
- Laravel: https://laravel.com
- GitHub: https://github.com/anthropics/claude-code

---

## 📝 REMEMBER

✅ API key in .env only
✅ Never commit .env
✅ Monitor API usage
✅ Keep key private
✅ Read logs on error
✅ Use gpt-3.5-turbo (cheaper)
✅ Clear context if needed
✅ Check OpenAI status

---

## 🎯 COMMON TASKS

### Add API Key
1. Get from OpenAI
2. Add to .env
3. Restart server

### Change Model
1. Edit .env
2. Set OPENAI_MODEL
3. Restart server

### Adjust Quality
1. Edit .env
2. Change MAX_TOKENS (higher = better)
3. Restart server

### Save Money
1. Edit .env
2. Lower MAX_TOKENS
3. Use gpt-3.5-turbo

### Clear Chat
1. Click "Clear" button
2. History clears
3. Start fresh

---

## 🚀 DEPLOY

1. Get API key
2. Set .env
3. Restart
4. Test
5. Go live!

---

## 📞 NEED HELP?

Read these in order:
1. CHATBOT_QUICK_START.md (5 min)
2. CHATBOT_SECURITY.md (15 min)
3. CHATBOT_FINAL_CHECKLIST.md (troubleshooting)

---

**Last Updated**: 2026-06-24  
**Status**: ✅ Ready  
**Version**: 1.0  

Print & keep handy! 📋

