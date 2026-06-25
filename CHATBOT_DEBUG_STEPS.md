# Trackit Chatbot — Debug & Testing Steps

## 🔍 Follow these steps to find and fix the issue

---

## Step 1: Verify API Key is Set ✅

### Check if OpenAI API key exists:

```bash
grep OPENAI_API_KEY .env
```

**Expected output:**
```
OPENAI_API_KEY=sk-proj-xxxxxxxxxxxxxxxxxxxxxxxxx
```

**If you see:** `# OPENAI_API_KEY=...` (commented out)
- **Solution:** Uncomment it (remove the #)
- **Then restart:** `php artisan serve`

**If you see:** Nothing
- **Solution:** Add the line to `.env`:
  ```env
  OPENAI_API_KEY=sk-your-api-key-here
  ```

---

## Step 2: Open Browser Developer Tools

### Open the Console:
1. **Windows/Linux:** Press `F12`
2. **Mac:** Press `Cmd + Option + I`
3. Go to the **Console** tab

**This is crucial!** The Console will show you error messages.

---

## Step 3: Test the Chatbot UI

### Open the chatbot:
1. Go to **http://localhost:8000/dashboard**
2. **Hover over** the chat icon (navbar top-right)
3. **Check the console** for errors

**Expected:** 
- Drawer slides in from the right
- No red error messages in console

**If you see errors:**
- Copy the error message
- Share it for support

---

## Step 4: Send a Test Message

### Type and send a message:
1. The chat drawer should now be open
2. Type: `hello`
3. Press **Enter**
4. **Check the console** (F12) for any messages
5. Look at the **Network tab** (F12 → Network)

**Expected:**
- "hello" appears on the right (blue bubble)
- "Thinking..." appears on the left
- AI response appears after 2-3 seconds

**If "Thinking..." never changes:**
- Check Network tab
- Look for `chatbot/send` request
- Check the response status and body

---

## Step 5: Check Network Tab

### In Developer Tools (F12):
1. Click **Network** tab
2. Send a message in chatbot
3. Look for a request to **`chatbot/send`**

### Check the request:
- **Method:** Should be `POST`
- **Status:** Should be `200` (success) or `503` (API not configured)
- **Response:** Should show JSON

### If status is `200`:
```json
{
  "success": true,
  "response": "AI message here"
}
```

### If status is `503`:
```json
{
  "success": false,
  "error": "AI Assistant is not configured"
}
```
→ **Solution:** Add `OPENAI_API_KEY` to `.env`

### If status is `500`:
→ **Laravel error** - Check `storage/logs/laravel.log`

---

## Step 6: Check Laravel Logs

### View recent errors:
```bash
tail -50 storage/logs/laravel.log
```

**Look for:**
- `ChatBot` errors
- `OpenAI` connection errors
- API key validation errors
- HTTP request failures

---

## Step 7: Test API Connectivity

### Check if OpenAI API is reachable:

```bash
# In Laravel Tinker
php artisan tinker
```

Then run:
```php
$service = new \App\Services\OpenAIService();
$service->testConnection();
```

**Expected:** `true`

**If false:**
- Check API key is valid
- Check internet connection
- Visit https://status.openai.com/

---

## Step 8: Full Integration Test

### Test the complete flow:

```bash
php artisan tinker
```

Run:
```php
$service = new \App\Services\OpenAIService();
$response = $service->sendMessage('hello', []);
echo $response;
```

**Expected:** AI response message

**If error:** Check error message in output

---

## 🐛 Common Issues & Quick Fixes

| Problem | Check | Solution |
|---------|-------|----------|
| "AI not configured" | API key in `.env` | Add `OPENAI_API_KEY=sk-...` |
| Network error | Network tab → `chatbot/send` status | Check internet, check API key |
| Timeout (waiting forever) | Network tab → Response time | Check if request completes, may be slow |
| Empty response | Laravel log | Check `storage/logs/laravel.log` |
| Chatbot won't open | Browser console (F12) | Check for JavaScript errors |

---

## ✅ Complete Verification Checklist

- [ ] `OPENAI_API_KEY` is in `.env` file
- [ ] `.env` file is NOT commented out
- [ ] Development server is running (`php artisan serve`)
- [ ] Page is refreshed (hard refresh: `Ctrl+Shift+R`)
- [ ] Browser cache is cleared
- [ ] Browser console (F12) shows no red errors
- [ ] Chat drawer opens when hovering over icon
- [ ] Can type in the input field
- [ ] Form submits when pressing Enter
- [ ] "Thinking..." message appears
- [ ] AI response appears after 2-3 seconds
- [ ] Network tab shows `chatbot/send` with status 200
- [ ] Response JSON shows `"success": true`

---

## 📞 If Still Not Working

### Try these steps:

1. **Hard refresh:**
   ```
   Ctrl+Shift+R (Windows/Linux)
   Cmd+Shift+R (Mac)
   ```

2. **Clear cache:**
   ```bash
   php artisan cache:clear
   ```

3. **Rebuild assets:**
   ```bash
   npm run build
   ```

4. **Restart server:**
   ```bash
   # Stop: Ctrl+C
   # Start:
   php artisan serve
   ```

5. **Check logs:**
   ```bash
   tail -100 storage/logs/laravel.log | grep -i "chatbot\|openai\|error"
   ```

---

## 🔧 If API Key is Invalid

### Test if your API key works:

```bash
curl -s https://api.openai.com/v1/models \
  -H "Authorization: Bearer sk-your-key-here" | head -20
```

**Expected:** List of models

**If error:** API key is invalid
- Get a new one at https://platform.openai.com/account/api-keys
- Update `.env` file
- Restart server

---

## 💡 Expected Console Messages (Good):

When everything works, you should see:
- No red error messages
- Form submits correctly
- Network request to `chatbot/send`
- JSON response with `success: true`

## ⚠️ Expected Error Messages (Indicates Problem):

**"Failed to fetch"** → Network issue, check if server is running

**"CORS error"** → Browser security, usually OK in development

**"API key not set"** → Add to `.env` and restart

**"Invalid API key format"** → Should start with `sk-`, check `.env`

**"Rate limit exceeded"** → Too many requests, wait a moment

---

## 📋 For Support

When reporting issues, include:
1. Output from `grep OPENAI_API_KEY .env`
2. Screenshot of browser console (F12)
3. Last 20 lines of `storage/logs/laravel.log`
4. Network tab screenshot showing `chatbot/send` request
5. Exact error message you see

---

**Your chatbot WILL work once configuration is correct!** ✨

*Last updated: June 25, 2026*
