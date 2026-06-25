# Kanban Board - Quick Start Guide

## 🚀 Get Started in 2 Minutes

### What You Get

✅ **3-column Kanban board** (Open, In Progress, Closed)
✅ **Drag-and-drop** issues between columns
✅ **Instant AJAX updates** - no page reload
✅ **Success notifications** - visual feedback
✅ **Beautiful UI** with issue details
✅ **Mobile responsive** design
✅ **Error handling** - automatic revert on failure

---

## 📋 Setup Steps

### 1. Build Assets
```bash
npm run build
```
This includes SortableJS library

### 2. Visit Kanban Board
```
http://localhost:8000/issues/kanban
```

### 3. Start Using
1. See 3 columns with issues grouped by status
2. Drag any issue card to another column
3. Watch it update instantly
4. See success notification

---

## 🎯 How to Use

### Drag Issues
1. **Hover** over an issue card
2. **Click and hold** the card
3. **Drag** to another column
4. **Drop** to update status

### View Issue Details
1. **Click** on issue title or project name
2. Opens full issue page

### Switch Views
- **Kanban view**: Click "Kanban" in nav
- **List view**: Click "List view" button
- **Issues index**: Shows filters and search

---

## 📊 Column Layout

### Open Column
- Issues that need to be started
- Click "New issue" to add

### In Progress Column
- Issues currently being worked on
- Drag here when starting work

### Closed Column
- Completed/resolved issues
- Drag here when finished

---

## 🎨 Issue Card Information

Each card shows:
- **Title** - Click to view full issue
- **Project name** - What project it belongs to
- **Priority badge** - Low/Medium/High (colored)
- **Tags** - Up to 2 tags, +N badge if more
- **Members** - Assigned users (avatar stack)
- **Comments** - Total comment count

---

## 💻 Technical Details

### What Happens When You Drag

1. **SortableJS detects** the drag
2. **AJAX request** sent to backend
3. **Database updated** with new status
4. **JSON response** returned
5. **DOM updated** to reflect change
6. **Notification** shown
7. **Card stays in place** (optimistic UI)

### AJAX Endpoint

```
PATCH /issues/{id}/kanban/status

Body: {
  "status": "in_progress",
  "position": 0
}
```

### Response

```json
{
  "success": true,
  "message": "Issue moved to in_progress",
  "issue": { ... }
}
```

---

## 🔧 Customization

### Change Notification Auto-hide Time

**File**: `resources/views/issues/kanban.blade.php`

```javascript
// Line ~230
setTimeout(() => {
    notification.classList.add('hidden');
}, 3000);  // 3000 = 3 seconds, change to 5000 for 5 seconds
```

### Change Animation Speed

**File**: `resources/views/issues/kanban.blade.php`

```javascript
// Line ~210
Sortable.create(column, {
    animation: 150,  // milliseconds, increase for slower drag
});
```

### Change Column Colors

**File**: `resources/views/issues/kanban.blade.php`

```php
// Line ~35
$columnColor = match($status) {
    'open' => ['bg-red-50', 'border-red-200', ...],  // Change colors
    'in_progress' => ['bg-yellow-50', 'border-yellow-200', ...],
    'closed' => ['bg-green-50', 'border-green-200', ...],
};
```

---

## 🧪 Testing

### Quick Test

1. **Create an issue** (if none exist)
2. **Drag issue** from Open to In Progress
3. **See notification** "Issue moved to in_progress"
4. **Check database** - issue should be updated
5. **Drag back** to Open (verify it works both ways)

### Test Error Handling

Try these scenarios:
- **Disable network** and try to drag (should revert)
- **Drag to same column** (should work fine)
- **Drag multiple issues** (should handle sequential updates)

---

## 🎯 Features Breakdown

### Drag-and-Drop
```
┌─────────────────────────────────┐
│ SortableJS initializes          │
├─────────────────────────────────┤
│ User drags issue                │
├─────────────────────────────────┤
│ Ghost effect shows              │
├─────────────────────────────────┤
│ User drops on column            │
├─────────────────────────────────┤
│ AJAX request sent               │
├─────────────────────────────────┤
│ Status updated in database      │
├─────────────────────────────────┤
│ Notification shown              │
└─────────────────────────────────┘
```

### Visual Feedback

**Hovering over card**:
- Shows "⋮ Drag to move" hint
- Card gains subtle shadow

**During drag**:
- Card becomes semi-transparent
- Ghost image follows cursor
- Other cards shift to show drop zone

**After drop**:
- Card returns to normal
- Notification appears
- Success message shown

---

## 🔒 Security

### CSRF Protection
- ✅ Token included in all requests
- ✅ Validated on backend

### Input Validation
- ✅ Status must be valid
- ✅ Issue must exist
- ✅ User can update issues

### Error Handling
- ✅ Failed updates are reverted
- ✅ Errors don't break UI
- ✅ User notified of problems

---

## 📱 Mobile Experience

### On Touch Devices
- Drag works with touch
- Drop zones are large
- Notifications are visible
- Layout is responsive

### Testing on Mobile
1. Open browser DevTools
2. Toggle device toolbar
3. Select mobile device
4. Test dragging
5. Verify notifications

---

## 🐛 If Drag Doesn't Work

### Check 1: Assets Built
```bash
npm run build
```

### Check 2: JavaScript Error
- Open DevTools (F12)
- Go to Console tab
- Look for red errors
- Check if SortableJS loaded

### Check 3: CSRF Token
- Right-click → Inspect
- Look for meta tag: `csrf-token`
- Should have value

### Check 4: Network Request
- Open DevTools → Network tab
- Drag issue
- Look for PATCH request
- Check response is success

---

## ❓ FAQ

### Q: Does dragging reload the page?
A: No! AJAX updates without reload.

### Q: What if the update fails?
A: Issue automatically reverts to original position.

### Q: Can I drag between any columns?
A: Yes, drag issues between Open, In Progress, and Closed.

### Q: How are issues ordered?
A: By creation date (newest first).

### Q: Can I reorder within a column?
A: Not yet - future feature coming!

### Q: Does it work on mobile?
A: Yes! Fully responsive.

### Q: What if I drag to the same column?
A: It still works - updates status to same value.

### Q: How long does notification show?
A: 3 seconds by default (customizable).

---

## 🎓 Understanding the Code

### Blade Template
```blade
@foreach ($statuses as $status)
    <div data-kanban-column="{{ $status }}">
        <!-- Issue cards here -->
    </div>
@endforeach
```

### JavaScript
```javascript
import Sortable from 'sortablejs';

Sortable.create(column, {
    group: 'issues',
    onEnd: async function(evt) {
        // Handle drag end
    }
});
```

### Controller
```php
public function updateStatus(Request $request, Issue $issue)
{
    $issue->update([
        'status' => $request->status
    ]);
    return response()->json(['success' => true]);
}
```

---

## 💡 Pro Tips

### Keyboard Navigation
- Tab through cards
- Enter to go to issue
- No drag with keyboard (future feature)

### Batch Operations
- Drag multiple issues individually
- No bulk drag yet
- Each drag triggers separate update

### Performance
- Large number of issues? Might scroll
- No lazy loading yet
- All issues load on page

### Customization Ideas
- Change column names
- Add custom statuses
- Add swimlanes (group by project)
- Implement WIP limits
- Add color coding by priority

---

## 🎨 UI Components

### Notification
```
┌─────────────────────────────────┐
│ ✓ Issue moved to in_progress   │
└─────────────────────────────────┘
(appears for 3 seconds)
```

### Empty Column
```
┌─────────────────────────────────┐
│                                 │
│    ⭕ No issues in this column   │
│                                 │
│    Drag issues here to move them │
│                                 │
└─────────────────────────────────┘
```

### Drag Indicator
```
⋮ Drag to move (appears on hover)
```

---

## 📊 Example Workflow

1. **Start day** - See all issues in Open column
2. **Pick issue** - See "Create auth" issue
3. **Start work** - Drag to In Progress
4. **See notification** - "Moved to in_progress"
5. **Work on issue** - Click to view details
6. **Finish work** - Drag to Closed
7. **See notification** - "Moved to closed"
8. **Next day** - See progress visually!

---

## 🚀 Next Steps

1. **Try dragging** - Get familiar with board
2. **Read documentation** - Understand architecture
3. **Customize** - Change colors, timing, etc.
4. **Deploy** - Push to production
5. **Monitor** - Check it's working
6. **Enhance** - Add more features

---

## 📞 Need Help?

1. Check FAQ above
2. Read full documentation: `KANBAN_BOARD_DOCUMENTATION.md`
3. Check browser console for errors
4. Verify assets are built
5. Test with sample data

---

## ✨ Summary

Your Kanban board is:
- ✅ **Fully functional** - drag-and-drop works
- ✅ **Production ready** - error handling included
- ✅ **Easy to use** - intuitive interface
- ✅ **Fast** - AJAX instant updates
- ✅ **Responsive** - works on all devices
- ✅ **Customizable** - change colors, timing
- ✅ **Maintainable** - clean code

---

**Start managing issues visually! 🎉**

Visit `/issues/kanban` and try dragging an issue!
