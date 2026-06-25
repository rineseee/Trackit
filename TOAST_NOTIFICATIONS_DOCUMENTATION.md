# Toast Notifications - Complete Implementation Guide

## 📖 Overview

A modern **toast notification system** for your Laravel application. Displays beautiful, non-intrusive notifications for success, error, warning, and info messages. Replaces browser `alert()` with elegant toast notifications that auto-dismiss.

---

## ✨ Features

✅ **Multiple Types** - Success, Error, Warning, Info
✅ **Auto-Dismiss** - Notifications disappear after 4-6 seconds
✅ **Session Notifications** - Display across page redirects
✅ **AJAX Notifications** - Use with fetch/AJAX requests
✅ **Global Access** - `window.toast` available everywhere
✅ **Customizable** - Change messages, titles, durations
✅ **No Browser Alert** - Replaces `alert()` completely
✅ **Stack Multiple** - Show many notifications at once
✅ **Progress Bar** - Visual countdown timer
✅ **Reusable** - Simple API across application

---

## 🏗️ Architecture

### Three Components

#### 1. Service (Backend)
**File**: `app/Services/NotificationService.php`

Manages notifications in session:
```php
$this->notification->success('Message', 'Title');
$this->notification->error('Message', 'Title');
$this->notification->warning('Message', 'Title');
$this->notification->info('Message', 'Title');
```

#### 2. JavaScript Module (Frontend)
**File**: `resources/js/toast-notification.js`

Displays notifications and handles AJAX:
```javascript
toast.success('Message', 'Title');
toast.error('Message', 'Title');
toast.warning('Message', 'Title');
toast.info('Message', 'Title');
```

#### 3. Blade Component
**File**: `resources/views/components/flash-notifications.blade.php`

Renders session notifications as JSON for JavaScript.

---

## 💻 Usage Examples

### Backend (Laravel)

#### In Controllers
```php
use App\Services\NotificationService;

class ProjectController extends Controller
{
    protected NotificationService $notification;

    public function __construct(NotificationService $notification)
    {
        $this->notification = $notification;
    }

    public function store(StoreProjectRequest $request)
    {
        $project = Project::create($request->validated());

        // Show success notification
        $this->notification->success(
            "Project \"{$project->name}\" created successfully.",
            'Project Created'
        );

        return redirect()->route('projects.show', $project);
    }
}
```

#### Multiple Notifications
```php
$this->notification->addMultiple([
    ['type' => 'success', 'message' => 'Data saved', 'title' => 'Success'],
    ['type' => 'warning', 'message' => 'Check validation', 'title' => 'Warning'],
]);
```

### Frontend (JavaScript)

#### Simple Notifications
```javascript
// Success
toast.success('Issue created successfully', 'Success');

// Error
toast.error('Failed to update issue', 'Error');

// Warning
toast.warning('This action cannot be undone', 'Warning');

// Info
toast.info('Loading data...', 'Please wait');
```

#### With AJAX
```javascript
// Simple POST with notifications
const response = await postWithToast('/issues', {
    title: 'Create issue',
    description: 'Fix bug'
}, {
    successMessage: 'Issue created successfully',
    errorMessage: 'Failed to create issue'
});

// Full fetch with options
const response = await fetchWithToast('/issues/1/status', {
    method: 'PATCH',
    body: JSON.stringify({ status: 'closed' }),
    successMessage: 'Issue closed successfully',
    successTitle: 'Closed',
    errorMessage: 'Failed to close issue',
    errorTitle: 'Error',
    showLoading: true,
    loadingMessage: 'Updating...'
});
```

#### Validation Errors
```javascript
try {
    const response = await fetch('/issues', { method: 'POST' });
    const data = await response.json();
    
    if (!response.ok) {
        // Show all validation errors as notifications
        showValidationErrors(data.errors);
    }
} catch (error) {
    toast.error(error.message);
}
```

---

## 🔌 API Reference

### NotificationService (Backend)

#### Methods

**success(message, title = 'Success')**
```php
$this->notification->success('Issue updated', 'Success');
```

**error(message, title = 'Error')**
```php
$this->notification->error('Failed to save', 'Error');
```

**warning(message, title = 'Warning')**
```php
$this->notification->warning('Data will be lost', 'Warning');
```

**info(message, title = 'Info')**
```php
$this->notification->info('Loading...', 'Please wait');
```

**addMultiple(array)**
```php
$this->notification->addMultiple([
    ['type' => 'success', 'message' => 'Saved', 'title' => 'Success'],
    ['type' => 'error', 'message' => 'Failed', 'title' => 'Error'],
]);
```

**get()**
```php
$notifications = $this->notification->get();
```

**getAndClear()**
```php
$notifications = $this->notification->getAndClear();
```

**clear()**
```php
$this->notification->clear();
```

**has()**
```php
if ($this->notification->has()) {
    // Has notifications
}
```

### JavaScript API

#### NotificationManager

**success(message, title = 'Success')**
```javascript
toast.success('Message', 'Title');
```

**error(message, title = 'Error')**
```javascript
toast.error('Message', 'Title');
```

**warning(message, title = 'Warning')**
```javascript
toast.warning('Message', 'Title');
```

**info(message, title = 'Info')**
```javascript
toast.info('Message', 'Title');
```

**multiple(notifications)**
```javascript
toast.multiple([
    { type: 'success', message: 'Saved', title: 'Success' },
    { type: 'error', message: 'Failed', title: 'Error' },
]);
```

**loading(message, title = 'Loading')**
```javascript
const id = toast.loading('Processing...', 'Please wait');
// Notification won't auto-dismiss
```

#### AJAX Helper Functions

**fetchWithToast(url, options)**
```javascript
const response = await fetchWithToast('/api/data', {
    method: 'POST',
    headers: { /* ... */ },
    body: JSON.stringify({ /* ... */ }),
    successMessage: 'Success',
    errorMessage: 'Failed',
    showLoading: true
});
```

**postWithToast(url, data, options)**
```javascript
const response = await postWithToast('/issues', {
    title: 'Create issue'
}, {
    successMessage: 'Created'
});
```

**patchWithToast(url, data, options)**
```javascript
const response = await patchWithToast('/issues/1', {
    status: 'closed'
}, {
    successMessage: 'Updated'
});
```

**deleteWithToast(url, options)**
```javascript
const response = await deleteWithToast('/issues/1', {
    successMessage: 'Deleted'
});
```

**showValidationErrors(errors)**
```javascript
showValidationErrors({
    title: ['Title is required'],
    description: ['Description must be at least 10 characters']
});
```

---

## 🎨 Notification Types

### Success (Green)
- Duration: 5 seconds
- Icon: Checkmark
- Use for: Successful operations
- Example: "Issue created successfully"

### Error (Red)
- Duration: 6 seconds (longer for reading)
- Icon: Cross
- Use for: Failed operations
- Example: "Failed to update issue"

### Warning (Orange)
- Duration: 5 seconds
- Icon: Exclamation
- Use for: Caution messages
- Example: "This action cannot be undone"

### Info (Blue)
- Duration: 4 seconds
- Icon: Info circle
- Use for: Informational messages
- Example: "Loading data..."

---

## 📊 Configuration

### Toast Library Settings

**File**: `resources/js/toast-notification.js` (line ~4)

```javascript
Toast.config({
    dir: 'ltr',           // Direction: ltr or rtl
    timeout: 5,           // Default timeout in seconds
    useProgressBar: true, // Show progress bar
});
```

### Per-Notification Duration

Modify the duration in notification calls:

```javascript
// Success with custom duration
const options = {
    type: 'success',
    duration: 3000,  // 3 seconds
};
Toast.info('Message', options);
```

### Colors & Styling

Toast colors are configured in the library. To customize, see Toast.js documentation.

---

## 🔄 Request/Response Flow

### Full Page Load (Session)
```
1. User action (form submit)
2. Controller validates & processes
3. Controller calls $notification->success()
4. Controller redirects to new page
5. Blade includes flash-notifications component
6. JavaScript reads JSON and displays toasts
7. User sees notification for 5 seconds
```

### AJAX Request
```
1. JavaScript calls fetchWithToast()
2. AJAX request sent to server
3. Server processes and returns JSON
4. JavaScript receives response
5. If success: show success toast
6. If error: show error toast
7. Toast auto-dismisses after duration
```

---

## 🧪 Testing

### Manual Test Cases

**Test 1: Create Project (Session)**
1. Click "New project"
2. Fill form and submit
3. Should see green success toast
4. Should be on project show page
5. Notification disappears after 5s

**Test 2: Update Issue (Session)**
1. Go to issue page
2. Click "Edit issue"
3. Change title and save
4. Should see green success toast
5. Should be back on issue page

**Test 3: Delete Project (Session)**
1. Go to project show page
2. Click "Delete"
3. Should see green success toast
4. Should be on projects index

**Test 4: Form Validation (AJAX)**
1. Use browser console
2. Run: `postWithToast('/projects', {})`
3. Should see red error toasts for validation
4. Each error shown separately

**Test 5: Multiple Notifications**
1. Use: `toast.multiple([...])`
2. Should stack notifications
3. Each has own timer
4. Can dismiss individually

---

## 🔒 Security

### CSRF Protection
```javascript
// Automatically included in all AJAX
headers: {
    'X-CSRF-TOKEN': getCsrfToken(),
}
```

### Input Validation
- Server-side validation required
- Errors sanitized before display
- HTML special characters escaped

---

## 📱 Responsive Design

Toast notifications work on:
- ✅ Desktop (fixed top-right corner)
- ✅ Tablet (responsive positioning)
- ✅ Mobile (optimized for small screens)
- ✅ Dark mode (if browser supports)

---

## 🚀 Performance

### Optimization
- Lightweight toast library (~5KB)
- No external dependencies (except js-toast)
- Auto-dismiss prevents notification buildup
- Efficient DOM updates

### Bundle Size Impact
- Base: ~5KB
- In app bundle: Negligible increase
- Load time: No perceivable impact

---

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| Notifications not showing | Check assets are built: `npm run build` |
| CSRF token missing | Verify `<meta name="csrf-token">` in layout |
| AJAX doesn't show notification | Use `fetchWithToast()` helper function |
| Toasts appear but disappear instantly | Check Toast.config timeout setting |
| Old notification types appearing | Clear browser cache |

---

## 📚 Integration Examples

### Example 1: Form Submission
```javascript
document.getElementById('myForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(this);
    const response = await postWithToast('/api/save', Object.fromEntries(formData), {
        successMessage: 'Saved successfully',
        errorMessage: 'Failed to save'
    });

    if (response.ok) {
        this.reset();
    }
});
```

### Example 2: Delete with Confirmation
```javascript
async function deleteItem(id) {
    if (!confirm('Are you sure?')) return;

    const response = await deleteWithToast(`/items/${id}`, {
        successMessage: 'Item deleted',
        errorMessage: 'Failed to delete'
    });

    if (response.ok) {
        // Remove from page
        document.getElementById(`item-${id}`).remove();
    }
}
```

### Example 3: Bulk Operations
```javascript
async function updateIssues(ids, status) {
    const loadingId = toast.loading(`Updating ${ids.length} issues...`);

    const response = await patchWithToast('/bulk/issues', {
        ids,
        status
    }, {
        successMessage: `${ids.length} issues updated`,
        errorMessage: 'Failed to update issues'
    });

    // Clear loading notification if needed
    if (response.ok) {
        location.reload();
    }
}
```

---

## 🔮 Future Enhancements

### Potential Features
- [ ] Undo action support
- [ ] Sound notifications
- [ ] Desktop notifications
- [ ] Notification history
- [ ] Custom animations
- [ ] Position customization
- [ ] Action buttons on toast
- [ ] Toast persistence

---

## ✅ Deployment Checklist

- [x] NotificationService created
- [x] JavaScript module created
- [x] Blade component created
- [x] Controllers updated
- [x] Assets compiled
- [x] Layout updated
- [ ] All controllers updated with notifications
- [ ] AJAX calls updated with toast helpers
- [ ] Tested all notification types
- [ ] Tested on mobile
- [ ] Tested validation errors
- [ ] Production ready

---

## 📞 Support

For issues:
1. Check browser console for errors
2. Verify assets are built
3. Check CSRF token exists
4. Test with simple notification first
5. Review console network tab for AJAX errors

---

**Toast Notifications Ready! 🎉**

Your application now has beautiful, modern notifications!
