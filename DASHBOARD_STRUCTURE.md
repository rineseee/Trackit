# Dashboard Visual Structure & Layout Guide

## 📐 Dashboard Layout Overview

```
┌─────────────────────────────────────────────────────────────────────┐
│                         HEADER (Navigation)                         │
│  Logo  |  Dashboard  |  Projects  |  Issues  |  Tags  |  User Menu │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│                                                                       │
│  Dashboard                                                            │
│  Welcome back! Here's what's happening with your projects.          │
│                                                                       │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│                     STATISTICS SECTION (4 CARDS)                    │
│                                                                       │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────┐ │
│  │ Total        │  │ Total        │  │ Open         │  │ Closed   │ │
│  │ Projects     │  │ Issues       │  │ Issues       │  │ Issues   │ │
│  │              │  │              │  │              │  │          │ │
│  │ 5            │  │ 50           │  │ 25           │  │ 20       │ │
│  │ +2.5%        │  │ +12%         │  │ -3%          │  │ +8%      │ │
│  └──────────────┘  └──────────────┘  └──────────────┘  └──────────┘ │
│                                                                       │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│                      CHARTS SECTION (2 COLUMNS)                     │
│                                                                       │
│  ┌─────────────────────────────┐  ┌──────────────────────────────┐  │
│  │ Issues by Status (Doughnut) │  │ Issues by Priority (Pie)     │  │
│  │                             │  │                              │  │
│  │      ╭─────────╮            │  │      ╱════╲                 │  │
│  │    ╱─────────────╲          │  │   ╱──────────╲              │  │
│  │   │               │         │  │  │            │             │  │
│  │   │     25%       │         │  │  │   33%      │             │  │
│  │   │               │         │  │   │            │             │  │
│  │    ╲─────────────╱          │  │    ╲──────────╱              │  │
│  │      ╰─────────╯            │  │      ╲════╱                 │  │
│  │                             │  │                              │  │
│  │  Open | In Progress | Closed│  │   Low | Medium | High       │  │
│  └─────────────────────────────┘  └──────────────────────────────┘  │
│                                                                       │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│                  ISSUES PER PROJECT CHART (Full Width)              │
│                                                                       │
│  Issues per Project (Bar Chart)                                     │
│                                                                       │
│   15 │                                                              │
│      │     ┌─────┐                                                 │
│   10 │     │     │     ┌─────┐                                     │
│      │     │     │     │     │  ┌─────┐                            │
│    5 │     │     │     │     │  │     │  ┌─────┐                  │
│      │     │     │     │     │  │     │  │     │  ┌─────┐         │
│    0 └─────┴─────┴─────┴─────┴──┴─────┴──┴─────┴──┴─────┴────     │
│      Proj1 Proj2 Proj3 Proj4 Proj5 Proj6 Proj7 Proj8 Proj9        │
│                                                                       │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│                  RECENT ACTIVITY SECTION (3 COLUMNS)               │
│                                                                       │
│  ┌────────────────────────────────┐  ┌────────────────────────┐   │
│  │  Recent Issues                  │  │  Recent Projects       │   │
│  │  ┌──────────────────────────────┤  │  ┌────────────────────┤   │
│  │  │ Issue 1                      │  │  │ Project A          │   │
│  │  │ Project A | [Open][Medium]   │  │  │ By John | 5 issues │   │
│  │  │ [JD] [ST]                    │  │  ├────────────────────┤   │
│  │  ├──────────────────────────────┤  │  │ Project B          │   │
│  │  │ Issue 2                      │  │  │ By Jane | 3 issues │   │
│  │  │ Project B | [In Progress][H] │  │  ├────────────────────┤   │
│  │  │ [MK] [ST] [LT]               │  │  │ Project C          │   │
│  │  ├──────────────────────────────┤  │  │ By Bob  | 2 issues │   │
│  │  │ Issue 3                      │  │  ├────────────────────┤   │
│  │  │ Project C | [Closed][Low]    │  │  │ Project D          │   │
│  │  │ [JD]                         │  │  │ By Alex | 1 issue  │   │
│  │  └──────────────────────────────┘  │  └────────────────────┘   │
│  │  View all →                        │  │ + New Project          │   │
│  └────────────────────────────────────┘  └────────────────────────┘   │
│                                                                       │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│                   STATUS SUMMARY SECTION (3 COLUMNS)               │
│                                                                       │
│  ┌──────────────────┐  ┌──────────────────┐  ┌──────────────────┐  │
│  │ Open (25)        │  │ In Progress (5)  │  │ Closed (20)      │  │
│  │                  │  │                  │  │                  │  │
│  │ [████████░░░░]   │  │ [██░░░░░░░░░░░]  │  │ [██████████░░░░] │  │
│  │                  │  │                  │  │                  │  │
│  │ View issues →    │  │ View issues →    │  │ View issues →    │  │
│  └──────────────────┘  └──────────────────┘  └──────────────────┘  │
│                                                                       │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 🎯 Component Grid System

### Desktop Layout (≥1024px)

```
Statistics Cards: 4 Columns
┌──────────────────────────────────────────────────────────────┐
│ [Card 1]  [Card 2]  [Card 3]  [Card 4]                       │
└──────────────────────────────────────────────────────────────┘

Charts: 2 Columns
┌───────────────────────────┬───────────────────────────┐
│ Chart 1 (Status)          │ Chart 2 (Priority)        │
├───────────────────────────┴───────────────────────────┤
│ Chart 3 (Projects) - Full Width                        │
└────────────────────────────────────────────────────────┘

Recent Activity: 3 Columns
┌───────────────────────────────┬─────────────────┐
│ Recent Issues (2 cols)        │ Recent Projects │
│                               │ (1 col)         │
├───────────────────────────────┴─────────────────┤
│ Status Summary - 3 equal columns                 │
└────────────────────────────────────────────────────┘
```

### Tablet Layout (640px - 1023px)

```
Statistics Cards: 2 Columns
┌──────────────────────┬──────────────────────┐
│ [Card 1]             │ [Card 2]             │
├──────────────────────┼──────────────────────┤
│ [Card 3]             │ [Card 4]             │
└──────────────────────┴──────────────────────┘

Charts: Stacked (Full Width)
┌────────────────────────────────────┐
│ Chart 1 (Status)                   │
├────────────────────────────────────┤
│ Chart 2 (Priority)                 │
├────────────────────────────────────┤
│ Chart 3 (Projects)                 │
└────────────────────────────────────┘

Recent Activity: Stacked
┌────────────────────────────────────┐
│ Recent Issues                      │
├────────────────────────────────────┤
│ Recent Projects                    │
├────────────────────────────────────┤
│ Status Summary (3 columns)         │
└────────────────────────────────────┘
```

### Mobile Layout (<640px)

```
All sections: Full Width, Stacked Vertically

┌────────────────────┐
│ [Card 1]           │
├────────────────────┤
│ [Card 2]           │
├────────────────────┤
│ [Card 3]           │
├────────────────────┤
│ [Card 4]           │
├────────────────────┤
│ Chart 1 (Status)   │
├────────────────────┤
│ Chart 2 (Priority) │
├────────────────────┤
│ Chart 3 (Projects) │
├────────────────────┤
│ Recent Issues      │
├────────────────────┤
│ Recent Projects    │
├────────────────────┤
│ Status Summary     │
└────────────────────┘
```

---

## 🎨 Color System

### Status Colors
```
┌─────────────────┬──────────────┬──────────────┐
│ Open            │ In Progress  │ Closed       │
│ Blue Background │ Amber BG     │ Green BG     │
│ #3b82f6         │ #f59e0b      │ #10b981      │
├─────────────────┼──────────────┼──────────────┤
│ ████████████    │ ████████████ │ ████████████ │
│ Text: #1e40af   │ Text: #d97706│ Text: #059669│
└─────────────────┴──────────────┴──────────────┘
```

### Priority Colors
```
┌──────────────────┬──────────────────┬──────────────┐
│ Low              │ Medium           │ High         │
│ Light Blue BG    │ Yellow BG        │ Light Red BG │
│ #93c5fd          │ #fcd34d          │ #fca5a5     │
├──────────────────┼──────────────────┼──────────────┤
│ ████████████     │ ████████████     │ ████████████ │
│ Text: #1e40af    │ Text: #d97706    │ Text: #dc2626│
└──────────────────┴──────────────────┴──────────────┘
```

### Card Styling
```
┌─────────────────────────────────────┐
│                                      │ ← border-slate-200/90
│  Card Content                        │
│  - Background: bg-white/95           │
│  - Padding: p-6 (24px)               │
│  - Border radius: rounded-lg (8px)   │
│  - Shadow: shadow-sm shadow-200/70   │
│  - Hover: shadow-md shadow-300/50    │
│                                      │
└─────────────────────────────────────┘
```

---

## 📊 Chart Specifications

### Status Chart (Doughnut)
- **Type**: Doughnut
- **Colors**: Blue, Amber, Green
- **Legend**: Bottom
- **Labels**: Open, In Progress, Closed
- **Data**: Issue counts per status

### Priority Chart (Pie)
- **Type**: Pie
- **Colors**: Light Blue, Yellow, Light Red
- **Legend**: Bottom
- **Labels**: Low, Medium, High
- **Data**: Issue counts per priority

### Projects Chart (Bar)
- **Type**: Horizontal Bar
- **Color**: Indigo
- **Legend**: Hidden
- **Labels**: Project names (top 6)
- **Data**: Issue count per project
- **Height**: 320px

---

## 🏷️ Typography Hierarchy

```
Page Title
├─ Size: 30px (text-3xl)
├─ Weight: Bold (font-bold)
├─ Color: slate-900
└─ Margin: mb-6

Section Heading
├─ Size: 18px (text-lg)
├─ Weight: Semibold (font-semibold)
├─ Color: slate-900
└─ Margin: mb-6

Stat Value
├─ Size: 30px (text-3xl)
├─ Weight: Bold (font-bold)
├─ Color: slate-900
└─ Display: Block

Card Label
├─ Size: 14px (text-sm)
├─ Weight: Medium (font-medium)
├─ Color: slate-600
└─ Margin: mb-2

Supporting Text
├─ Size: 14px (text-sm)
├─ Weight: Regular (font-normal)
├─ Color: slate-600
└─ Line Height: 1.5

Badge Label
├─ Size: 12px (text-xs)
├─ Weight: Semibold (font-semibold)
├─ Color: Status/Priority specific
└─ Padding: px-2.5 py-1
```

---

## 🧩 Component Breakdown

### Stat Card Structure
```
┌────────────────────────────────┐
│ ╭─ Title (sm, medium)          │
│                                │
│ Value      [Icon]              │
│ +2.5%                          │
│                                │
│ ─────────────────────          │
│ View details →                 │
└────────────────────────────────┘
```

### Recent Issue Item Structure
```
┌────────────────────────────────┐
│ Title              [Status][Pr]│
│ Project Name                   │
│ [Avatar1][Avatar2][Avatar3]    │
└────────────────────────────────┘
```

### Badge Structure
```
Badge with Color
┌──────────────────┐
│ Label (centered) │ ← Colored background
│ text-xs bold     │ ← Colored text
└──────────────────┘
```

---

## 📐 Spacing & Padding

### Standard Measurements
```
Container: max-w-7xl
Grid Gap: gap-6 (24px)
Card Padding: p-6 (24px)
Margin Top: mt-6, mt-4 (varies by context)
Border Radius: rounded-lg (8px)
```

### Card Spacing
```
┌──────────────────┐
│ p-6              │ 24px padding
│ ┌──────────────┐ │
│ │   Content    │ │
│ └──────────────┘ │
└──────────────────┘
```

---

## 🔄 Responsive Behavior

### Stat Cards
```
Mobile:  1 col (full width)
Tablet:  2 cols (sm: grid-cols-2)
Desktop: 4 cols (lg: grid-cols-4)
```

### Charts
```
Mobile:  1 col (stacked)
Tablet:  1 col (stacked)
Desktop: 2 cols (lg: grid-cols-2)
          Full width (projects)
```

### Recent Activity
```
Mobile:  1 col (stacked)
Tablet:  1 col (stacked)
Desktop: 3 cols (lg: grid-cols-3)
         - Recent Issues: 2 cols
         - Recent Projects: 1 col
```

---

## 🎯 Interaction States

### Cards
```
Default: shadow-sm
Hover:   shadow-md (darker/stronger)
Focus:   outline-2 outline-blue-500
Active:  bg-slate-50 (slight tint)
```

### Buttons
```
Default: opacity-100, cursor-pointer
Hover:   opacity-90, scale-105
Focus:   outline-2 outline-offset-2
Active:  scale-95 (press effect)
```

### Links
```
Default: text-blue-600
Hover:   text-blue-700 (darker)
Focus:   outline-2
Active:  text-blue-800
```

---

## 📏 Dimensions Reference

| Element | Value |
|---------|-------|
| Page max-width | 80rem (1280px) |
| Card min-height | auto |
| Chart height | 288px (h-72) |
| Projects chart | 320px (h-80) |
| Stat icon | 48px (h-12 w-12) |
| Member avatar | 28px (h-7 w-7) |
| Border radius | 8px (rounded-lg) |
| Shadow blur | 4px |

---

## 🔀 Stacking Order (Z-index)

```
Modal/Popup:    z-50 (highest)
Header:         z-20
Dropdown:       z-10
Default:        z-0 (lowest)
```

---

## ✨ Animations

### Skeleton Loading
```css
animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
```

### Slide In
```css
@keyframes slideIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
```

### Transitions
```css
transition: all 150ms ease;
transition: shadow 150ms ease;
transition: transform 150ms ease;
```

---

## 🖨️ Print Styles

Not specifically styled for print. Consider adding:
- Hide navigation
- Hide interactive elements
- Optimize colors for grayscale
- Adjust margins/padding
- Landscape for charts

---

**This visual structure ensures a modern, clean, and professional SaaS dashboard experience! 🎨**
