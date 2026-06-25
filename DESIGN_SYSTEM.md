# SaaS Design System - Complete Implementation Guide

**Goal**: Transform your application into a polished commercial SaaS platform comparable to Jira/Linear/ClickUp

**Scope**: Design system, component library, layouts, AI assistant, page redesigns

---

## 1. COLOR PALETTE

### Primary Colors
```css
:root {
    --primary-50: #f0f9ff;      /* Lightest */
    --primary-100: #e0f2fe;
    --primary-200: #bae6fd;
    --primary-300: #7dd3fc;
    --primary-400: #38bdf8;
    --primary-500: #0ea5e9;     /* Brand Color */
    --primary-600: #0284c7;
    --primary-700: #0369a1;
    --primary-800: #075985;
    --primary-900: #0c2d6b;     /* Darkest */
}
```

### Semantic Colors
```css
:root {
    /* Status Colors */
    --success: #10b981;         /* Green - Done/Closed */
    --warning: #f59e0b;         /* Amber - In Progress */
    --danger: #ef4444;          /* Red - Open/Urgent */
    --info: #3b82f6;            /* Blue - Information */
    --muted: #6b7280;           /* Gray - Disabled/Muted */
    
    /* Backgrounds */
    --bg-primary: #ffffff;
    --bg-secondary: #f8fafc;
    --bg-tertiary: #f1f5f9;
    
    /* Text */
    --text-primary: #0f172a;
    --text-secondary: #475569;
    --text-tertiary: #94a3b8;
    
    /* Borders */
    --border-light: #e2e8f0;
    --border-medium: #cbd5e1;
    --border-dark: #94a3b8;
}
```

### Dark Mode (Future)
```css
@media (prefers-color-scheme: dark) {
    :root {
        --bg-primary: #1e293b;
        --bg-secondary: #0f172a;
        --text-primary: #f1f5f9;
        --text-secondary: #cbd5e1;
    }
}
```

---

## 2. TYPOGRAPHY SYSTEM

### Font Stack
```css
:root {
    --font-sans: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    --font-mono: "Fira Code", "Monaco", monospace;
}

body {
    font-family: var(--font-sans);
    font-size: 16px;
    line-height: 1.5;
    color: var(--text-primary);
}
```

### Type Scale
```css
/* Headings */
.h1 {
    font-size: 2.25rem;  /* 36px */
    font-weight: 800;
    line-height: 1.2;
    letter-spacing: -0.02em;
}

.h2 {
    font-size: 1.875rem; /* 30px */
    font-weight: 700;
    line-height: 1.25;
    letter-spacing: -0.01em;
}

.h3 {
    font-size: 1.5rem;   /* 24px */
    font-weight: 700;
    line-height: 1.4;
}

.h4 {
    font-size: 1.25rem;  /* 20px */
    font-weight: 600;
    line-height: 1.4;
}

/* Body Text */
.text-lg {
    font-size: 1.125rem; /* 18px */
    font-weight: 400;
    line-height: 1.6;
}

.text-base {
    font-size: 1rem;     /* 16px */
    font-weight: 400;
    line-height: 1.5;
}

.text-sm {
    font-size: 0.875rem; /* 14px */
    font-weight: 400;
    line-height: 1.5;
}

.text-xs {
    font-size: 0.75rem;  /* 12px */
    font-weight: 500;
    line-height: 1.5;
}

/* Variants */
.font-bold { font-weight: 700; }
.font-semibold { font-weight: 600; }
.font-medium { font-weight: 500; }
.font-normal { font-weight: 400; }

.text-muted { color: var(--text-tertiary); }
.text-secondary { color: var(--text-secondary); }
```

---

## 3. SPACING SYSTEM

```css
:root {
    /* Spacing Scale (4px base) */
    --space-0: 0;
    --space-1: 0.25rem;  /* 4px */
    --space-2: 0.5rem;   /* 8px */
    --space-3: 0.75rem;  /* 12px */
    --space-4: 1rem;     /* 16px */
    --space-5: 1.25rem;  /* 20px */
    --space-6: 1.5rem;   /* 24px */
    --space-8: 2rem;     /* 32px */
    --space-10: 2.5rem;  /* 40px */
    --space-12: 3rem;    /* 48px */
    --space-16: 4rem;    /* 64px */
}

/* Standard page structure */
.page-header { padding: var(--space-8) 0; }
.page-section { margin-bottom: var(--space-12); }
.card { padding: var(--space-6); margin-bottom: var(--space-6); }
```

---

## 4. COMPONENT LIBRARY

### Button System

**Base Button Styles**
```blade
<!-- resources/views/components/button.blade.php -->
<button 
    type="{{ $type ?? 'button' }}"
    class="btn btn-{{ $variant ?? 'primary' }} btn-{{ $size ?? 'md' }}"
    @if($disabled) disabled @endif
    @if($onclick) onclick="{{ $onclick }}" @endif
>
    @if($icon)
        <i class="icon icon-{{ $icon }}"></i>
    @endif
    {{ $slot }}
</button>

<style>
/* Base Button */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-2);
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: all 150ms ease-in-out;
    white-space: nowrap;
}

/* Sizes */
.btn-xs {
    padding: var(--space-2) var(--space-3);
    font-size: 0.75rem;
}

.btn-sm {
    padding: var(--space-2) var(--space-4);
    font-size: 0.875rem;
}

.btn-md {
    padding: var(--space-2) var(--space-6);
    font-size: 1rem;
}

.btn-lg {
    padding: var(--space-3) var(--space-8);
    font-size: 1.125rem;
}

/* Variants */
.btn-primary {
    background: var(--primary-500);
    color: white;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.btn-primary:hover {
    background: var(--primary-600);
    box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
    transform: translateY(-1px);
}

.btn-primary:active {
    transform: translateY(0);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.btn-secondary {
    background: var(--bg-secondary);
    color: var(--text-primary);
    border: 1px solid var(--border-light);
}

.btn-secondary:hover {
    background: var(--bg-tertiary);
    border-color: var(--border-medium);
}

.btn-ghost {
    background: transparent;
    color: var(--primary-500);
    border: none;
}

.btn-ghost:hover {
    background: var(--primary-50);
    color: var(--primary-600);
}

.btn-danger {
    background: var(--danger);
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.btn-success {
    background: var(--success);
    color: white;
}

.btn-success:hover {
    background: #059669;
}

.btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}
</style>
```

**Usage Examples**
```blade
<!-- Primary Action -->
<x-button variant="primary" size="lg" icon="plus">Create Issue</x-button>

<!-- Secondary Action -->
<x-button variant="secondary">Cancel</x-button>

<!-- Danger Action -->
<x-button variant="danger" onclick="confirm('Delete?')">Delete</x-button>

<!-- Ghost Link -->
<x-button variant="ghost">View Details</x-button>
```

### Card Component

```blade
<!-- resources/views/components/card.blade.php -->
<div class="card {{ $class ?? '' }}">
    @if($title)
        <div class="card-header">
            <h3 class="card-title">{{ $title }}</h3>
            @if($headerAction)
                <div class="card-actions">
                    {{ $headerAction }}
                </div>
            @endif
        </div>
    @endif
    
    <div class="card-body">
        {{ $slot }}
    </div>
    
    @if($footer)
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endif
</div>

<style>
.card {
    background: var(--bg-primary);
    border: 1px solid var(--border-light);
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: all 200ms ease;
}

.card:hover {
    border-color: var(--border-medium);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--space-6);
    border-bottom: 1px solid var(--border-light);
}

.card-title {
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0;
}

.card-body {
    padding: var(--space-6);
}

.card-footer {
    padding: var(--space-6);
    border-top: 1px solid var(--border-light);
    background: var(--bg-secondary);
    border-radius: 0 0 12px 12px;
    display: flex;
    gap: var(--space-4);
}
</style>
```

### Badge Component

```blade
<!-- resources/views/components/badge.blade.php -->
<span class="badge badge-{{ $color ?? 'gray' }}">
    @if($icon) <i class="icon icon-{{ $icon }}"></i> @endif
    {{ $slot }}
</span>

<style>
.badge {
    display: inline-flex;
    align-items: center;
    gap: var(--space-2);
    padding: var(--space-1) var(--space-3);
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    white-space: nowrap;
}

.badge-blue { background: var(--primary-100); color: var(--primary-700); }
.badge-green { background: #dcfce7; color: #166534; }
.badge-red { background: #fee2e2; color: #991b1b; }
.badge-amber { background: #fef3c7; color: #92400e; }
.badge-gray { background: var(--bg-tertiary); color: var(--text-secondary); }

/* For status badges */
.badge-open { background: #fee2e2; color: #991b1b; }
.badge-in-progress { background: #fef3c7; color: #92400e; }
.badge-closed { background: #dcfce7; color: #166534; }
</style>
```

### Form Component

```blade
<!-- resources/views/components/form-group.blade.php -->
<div class="form-group">
    @if($label)
        <label class="form-label" for="{{ $name }}">
            {{ $label }}
            @if($required) <span class="text-danger">*</span> @endif
        </label>
    @endif
    
    {{ $slot }}
    
    @if($description)
        <p class="form-description">{{ $description }}</p>
    @endif
    
    @error($name)
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>

<style>
.form-group {
    margin-bottom: var(--space-6);
}

.form-label {
    display: block;
    margin-bottom: var(--space-2);
    font-weight: 600;
    font-size: 0.875rem;
    color: var(--text-primary);
}

.form-description {
    margin-top: var(--space-2);
    font-size: 0.875rem;
    color: var(--text-tertiary);
}

.form-error {
    margin-top: var(--space-2);
    font-size: 0.875rem;
    color: var(--danger);
}

input[type="text"],
input[type="email"],
input[type="password"],
input[type="number"],
input[type="date"],
input[type="time"],
textarea,
select {
    width: 100%;
    padding: var(--space-2) var(--space-4);
    border: 1px solid var(--border-light);
    border-radius: 6px;
    font-family: var(--font-sans);
    font-size: 1rem;
    transition: all 200ms ease;
}

input:focus,
textarea:focus,
select:focus {
    outline: none;
    border-color: var(--primary-500);
    box-shadow: 0 0 0 3px var(--primary-50);
}

input:disabled,
textarea:disabled,
select:disabled {
    background: var(--bg-secondary);
    color: var(--text-tertiary);
    cursor: not-allowed;
}
</style>
```

---

## 5. MASTER LAYOUT SYSTEM

### Main Layout

```blade
<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Issue Tracker')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <div class="app-layout">
        <!-- Header -->
        @include('layouts.partials.header')
        
        <!-- Main Container -->
        <div class="app-main">
            <!-- Sidebar -->
            @include('layouts.partials.sidebar')
            
            <!-- Content Area -->
            <main class="app-content">
                <!-- Page Header -->
                @if(isset($pageTitle))
                    <div class="page-header">
                        <div>
                            <h1 class="h1">@yield('page-title', $pageTitle ?? '')</h1>
                            @if(isset($pageDescription))
                                <p class="text-secondary">{{ $pageDescription }}</p>
                            @endif
                        </div>
                        @if(isset($pageActions))
                            <div class="page-actions">
                                {{ $pageActions }}
                            </div>
                        @endif
                    </div>
                @endif
                
                <!-- Flash Messages -->
                @include('layouts.partials.flash-messages')
                
                <!-- Page Content -->
                <div class="page-body">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    
    <!-- AI Assistant (Floating) -->
    @include('components.ai-assistant')
    
    <!-- Modals -->
    @include('layouts.partials.modals')
    
    @stack('scripts')
</body>

</html>

<style>
.app-layout {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.app-main {
    display: flex;
    flex: 1;
}

.app-content {
    flex: 1;
    overflow-y: auto;
    background: var(--bg-secondary);
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: var(--space-8);
    padding: var(--space-8);
    background: var(--bg-primary);
    border-bottom: 1px solid var(--border-light);
}

.page-actions {
    display: flex;
    gap: var(--space-4);
}

.page-body {
    padding: var(--space-8);
    max-width: 1400px;
    margin: 0 auto;
    width: 100%;
}

/* Responsive */
@media (max-width: 768px) {
    .app-main {
        flex-direction: column;
    }
    
    .page-header {
        flex-direction: column;
        gap: var(--space-4);
    }
    
    .page-body {
        padding: var(--space-4);
    }
}
</style>
```

### Header Component

```blade
<!-- resources/views/layouts/partials/header.blade.php -->
<header class="app-header">
    <div class="header-container">
        <!-- Logo -->
        <div class="header-logo">
            <a href="{{ route('dashboard') }}" class="logo-link">
                <div class="logo">IT</div>
                <span class="logo-text">Issue Tracker</span>
            </a>
        </div>
        
        <!-- Search Bar (Global) -->
        <div class="header-search">
            <input 
                type="text" 
                placeholder="Search issues, projects..." 
                class="search-input"
                id="global-search"
            >
            <i class="bi bi-search"></i>
        </div>
        
        <!-- Navigation -->
        <nav class="header-nav">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                Dashboard
            </a>
            <a href="{{ route('projects.index') }}" class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                <i class="bi bi-folder"></i>
                Projects
            </a>
            <a href="{{ route('issues.index') }}" class="nav-link {{ request()->routeIs('issues.*') ? 'active' : '' }}">
                <i class="bi bi-list-check"></i>
                Issues
            </a>
            <a href="{{ route('issues.kanban') }}" class="nav-link {{ request()->routeIs('issues.kanban') ? 'active' : '' }}">
                <i class="bi bi-kanban"></i>
                Kanban
            </a>
            <a href="{{ route('tags.index') }}" class="nav-link {{ request()->routeIs('tags.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i>
                Tags
            </a>
        </nav>
        
        <!-- Right Actions -->
        <div class="header-right">
            <!-- Notifications -->
            <button class="header-icon-btn" title="Notifications">
                <i class="bi bi-bell"></i>
                <span class="badge-dot"></span>
            </button>
            
            <!-- User Menu -->
            <div class="user-menu">
                <button class="user-button" id="user-menu-toggle">
                    <div class="user-avatar">{{ auth()->user()->name[0] }}</div>
                    <span class="user-name">{{ auth()->user()->name }}</span>
                    <i class="bi bi-chevron-down"></i>
                </button>
                
                <div class="dropdown-menu" id="user-menu-dropdown">
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="bi bi-person"></i> Profile
                    </a>
                    <a href="{{ route('settings.index') }}" class="dropdown-item">
                        <i class="bi bi-gear"></i> Settings
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}" class="dropdown-item">
                        @csrf
                        <button type="submit" class="dropdown-button">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<style>
.app-header {
    background: var(--bg-primary);
    border-bottom: 1px solid var(--border-light);
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.header-container {
    display: flex;
    align-items: center;
    gap: var(--space-8);
    padding: var(--space-4) var(--space-8);
    max-width: 1600px;
    margin: 0 auto;
}

.header-logo {
    min-width: fit-content;
}

.logo-link {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    text-decoration: none;
}

.logo {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    background: var(--primary-500);
    color: white;
    border-radius: 8px;
    font-weight: 700;
    font-size: 0.875rem;
}

.logo-text {
    font-weight: 700;
    color: var(--text-primary);
    white-space: nowrap;
}

.header-search {
    flex: 1;
    max-width: 400px;
    position: relative;
}

.search-input {
    width: 100%;
    padding: var(--space-2) var(--space-4) var(--space-2) var(--space-10);
    border: 1px solid var(--border-light);
    border-radius: 8px;
    background: var(--bg-secondary);
    font-size: 0.875rem;
}

.search-input:focus {
    outline: none;
    background: var(--bg-primary);
    border-color: var(--primary-500);
    box-shadow: 0 0 0 3px var(--primary-50);
}

.header-search i {
    position: absolute;
    left: var(--space-4);
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-tertiary);
}

.header-nav {
    display: flex;
    gap: var(--space-2);
}

.nav-link {
    display: flex;
    align-items: center;
    gap: var(--space-2);
    padding: var(--space-2) var(--space-4);
    color: var(--text-secondary);
    text-decoration: none;
    border-radius: 6px;
    font-weight: 500;
    font-size: 0.875rem;
    transition: all 150ms ease;
    white-space: nowrap;
}

.nav-link:hover {
    background: var(--bg-secondary);
    color: var(--text-primary);
}

.nav-link.active {
    background: var(--primary-50);
    color: var(--primary-600);
}

.header-right {
    display: flex;
    align-items: center;
    gap: var(--space-4);
    margin-left: auto;
}

.header-icon-btn {
    position: relative;
    width: 40px;
    height: 40px;
    border: none;
    background: transparent;
    border-radius: 6px;
    cursor: pointer;
    color: var(--text-secondary);
    transition: all 150ms ease;
}

.header-icon-btn:hover {
    background: var(--bg-secondary);
    color: var(--text-primary);
}

.badge-dot {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 6px;
    height: 6px;
    background: var(--danger);
    border-radius: 50%;
}

.user-menu {
    position: relative;
}

.user-button {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    padding: var(--space-1) var(--space-2) var(--space-1) var(--space-1);
    background: transparent;
    border: 1px solid var(--border-light);
    border-radius: 8px;
    cursor: pointer;
    transition: all 150ms ease;
}

.user-button:hover {
    background: var(--bg-secondary);
    border-color: var(--border-medium);
}

.user-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: var(--primary-100);
    color: var(--primary-700);
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.875rem;
}

.user-name {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-primary);
}

.dropdown-menu {
    position: absolute;
    top: calc(100% + var(--space-2));
    right: 0;
    background: var(--bg-primary);
    border: 1px solid var(--border-light);
    border-radius: 8px;
    min-width: 200px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    display: none;
}

.dropdown-menu.active {
    display: block;
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    padding: var(--space-3) var(--space-4);
    color: var(--text-primary);
    text-decoration: none;
    border: none;
    background: transparent;
    width: 100%;
    text-align: left;
    cursor: pointer;
    transition: background 150ms ease;
}

.dropdown-item:hover {
    background: var(--bg-secondary);
}

.dropdown-button {
    border: none;
    background: transparent;
    color: inherit;
    cursor: pointer;
}

.dropdown-divider {
    height: 1px;
    background: var(--border-light);
    margin: var(--space-2) 0;
}

/* Responsive */
@media (max-width: 1024px) {
    .header-search {
        max-width: 200px;
    }
    
    .user-name {
        display: none;
    }
}

@media (max-width: 768px) {
    .header-container {
        gap: var(--space-4);
        padding: var(--space-4);
    }
    
    .header-search {
        max-width: 150px;
    }
    
    .header-nav {
        display: none;
    }
}
</style>
```

### Sidebar Component

```blade
<!-- resources/views/layouts/partials/sidebar.blade.php -->
<aside class="app-sidebar">
    <nav class="sidebar-nav">
        <div class="sidebar-section">
            <h3 class="sidebar-section-title">Main</h3>
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </div>
        
        <div class="sidebar-section">
            <h3 class="sidebar-section-title">Projects</h3>
            <a href="{{ route('projects.index') }}" class="sidebar-link {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                <i class="bi bi-folder"></i>
                <span>All Projects</span>
            </a>
            <a href="{{ route('projects.create') }}" class="sidebar-link">
                <i class="bi bi-plus-lg"></i>
                <span>New Project</span>
            </a>
        </div>
        
        <div class="sidebar-section">
            <h3 class="sidebar-section-title">Issues</h3>
            <a href="{{ route('issues.index') }}" class="sidebar-link {{ request()->routeIs('issues.index') ? 'active' : '' }}">
                <i class="bi bi-list-check"></i>
                <span>All Issues</span>
            </a>
            <a href="{{ route('issues.kanban') }}" class="sidebar-link {{ request()->routeIs('issues.kanban') ? 'active' : '' }}">
                <i class="bi bi-kanban"></i>
                <span>Kanban Board</span>
            </a>
            <a href="{{ route('issues.create') }}" class="sidebar-link">
                <i class="bi bi-plus-lg"></i>
                <span>New Issue</span>
            </a>
        </div>
        
        <div class="sidebar-section">
            <h3 class="sidebar-section-title">Organization</h3>
            <a href="{{ route('tags.index') }}" class="sidebar-link {{ request()->routeIs('tags.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i>
                <span>Tags</span>
            </a>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>Users</span>
                </a>
            @endif
        </div>
    </nav>
    
    <!-- Toggle Button (Mobile) -->
    <button class="sidebar-toggle" id="sidebar-toggle">
        <i class="bi bi-list"></i>
    </button>
</aside>

<style>
.app-sidebar {
    width: 260px;
    background: var(--bg-primary);
    border-right: 1px solid var(--border-light);
    padding: var(--space-8) 0;
    overflow-y: auto;
    height: 100vh;
    position: sticky;
    top: 0;
}

.sidebar-nav {
    display: flex;
    flex-direction: column;
    gap: var(--space-6);
}

.sidebar-section {
    padding: 0 var(--space-4);
}

.sidebar-section-title {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--text-tertiary);
    margin-bottom: var(--space-3);
    padding: 0 var(--space-2);
}

.sidebar-link {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    padding: var(--space-3) var(--space-4);
    color: var(--text-secondary);
    text-decoration: none;
    border-radius: 6px;
    font-weight: 500;
    font-size: 0.875rem;
    transition: all 150ms ease;
    margin-bottom: var(--space-1);
}

.sidebar-link:hover {
    background: var(--bg-secondary);
    color: var(--text-primary);
}

.sidebar-link.active {
    background: var(--primary-50);
    color: var(--primary-600);
    font-weight: 600;
}

.sidebar-toggle {
    display: none;
    position: fixed;
    bottom: var(--space-4);
    left: var(--space-4);
    width: 44px;
    height: 44px;
    border-radius: 8px;
    background: var(--primary-500);
    color: white;
    border: none;
    cursor: pointer;
    z-index: 50;
}

/* Responsive */
@media (max-width: 1024px) {
    .app-sidebar {
        width: 200px;
    }
}

@media (max-width: 768px) {
    .app-sidebar {
        position: fixed;
        left: 0;
        top: 0;
        height: 100vh;
        z-index: 40;
        transform: translateX(-100%);
        transition: transform 300ms ease;
        width: 260px;
        padding-top: 60px;
    }
    
    .app-sidebar.active {
        transform: translateX(0);
    }
    
    .sidebar-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
    }
}
</style>
```

---

## 6. AI ASSISTANT COMPONENT

### Floating AI Button

```blade
<!-- resources/views/components/ai-assistant.blade.php -->
<div class="ai-assistant-widget">
    <!-- Floating Button -->
    <button class="ai-button" id="ai-toggle" title="Open AI Assistant">
        <i class="bi bi-sparkles"></i>
    </button>
    
    <!-- AI Panel -->
    <div class="ai-panel" id="ai-panel">
        <div class="ai-header">
            <h3>AI Assistant</h3>
            <button class="ai-close" id="ai-close">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <div class="ai-messages" id="ai-messages">
            <div class="ai-message ai-message-bot">
                <p>Hi! 👋 I'm your AI assistant. I can help you with:</p>
                <ul>
                    <li>Finding issues by status or priority</li>
                    <li>Summarizing projects</li>
                    <li>Suggesting priorities or assignees</li>
                    <li>Creating issues from descriptions</li>
                    <li>Generating reports</li>
                </ul>
                <p>Try asking: <strong>"Show my overdue issues"</strong></p>
            </div>
        </div>
        
        <div class="ai-input-area">
            <form id="ai-form" @submit.prevent="sendMessage">
                <input 
                    type="text" 
                    id="ai-input"
                    placeholder="Ask me anything..."
                    class="ai-input"
                    autocomplete="off"
                >
                <button type="submit" class="ai-send-btn">
                    <i class="bi bi-send"></i>
                </button>
            </form>
        </div>
    </div>
    
    <!-- Overlay (Mobile) -->
    <div class="ai-overlay" id="ai-overlay"></div>
</div>

<style>
.ai-assistant-widget {
    position: fixed;
    bottom: var(--space-8);
    right: var(--space-8);
    font-family: var(--font-sans);
    z-index: 999;
}

.ai-button {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: var(--primary-500);
    color: white;
    border: none;
    cursor: pointer;
    font-size: 1.5rem;
    box-shadow: 0 4px 12px rgba(14, 165, 233, 0.4);
    transition: all 200ms ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.ai-button:hover {
    background: var(--primary-600);
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(14, 165, 233, 0.5);
}

.ai-button.active {
    background: var(--primary-700);
}

.ai-panel {
    position: fixed;
    bottom: 100px;
    right: var(--space-8);
    width: 400px;
    max-height: 600px;
    background: var(--bg-primary);
    border: 1px solid var(--border-light);
    border-radius: 12px;
    box-shadow: 0 20px 25px rgba(0, 0, 0, 0.15);
    display: none;
    flex-direction: column;
    z-index: 1000;
}

.ai-panel.active {
    display: flex;
}

.ai-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--space-4);
    border-bottom: 1px solid var(--border-light);
}

.ai-header h3 {
    font-size: 1rem;
    font-weight: 600;
    margin: 0;
}

.ai-close {
    width: 32px;
    height: 32px;
    border: none;
    background: transparent;
    cursor: pointer;
    border-radius: 6px;
    color: var(--text-secondary);
    transition: all 150ms ease;
}

.ai-close:hover {
    background: var(--bg-secondary);
    color: var(--text-primary);
}

.ai-messages {
    flex: 1;
    overflow-y: auto;
    padding: var(--space-4);
    display: flex;
    flex-direction: column;
    gap: var(--space-4);
}

.ai-message {
    display: flex;
    gap: var(--space-3);
    animation: fadeIn 200ms ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.ai-message-bot {
    justify-content: flex-start;
}

.ai-message-user {
    justify-content: flex-end;
}

.ai-message p {
    margin: 0;
    padding: var(--space-3) var(--space-4);
    border-radius: 8px;
    font-size: 0.875rem;
    line-height: 1.5;
    max-width: 85%;
}

.ai-message-bot p {
    background: var(--bg-secondary);
    color: var(--text-primary);
}

.ai-message-user p {
    background: var(--primary-500);
    color: white;
}

.ai-message ul {
    list-style: none;
    padding: 0 var(--space-4);
    margin: var(--space-2) 0;
}

.ai-message li {
    font-size: 0.875rem;
    padding: var(--space-1) 0;
    color: var(--text-primary);
}

.ai-message li:before {
    content: "• ";
    color: var(--primary-500);
    font-weight: 600;
}

.ai-input-area {
    padding: var(--space-4);
    border-top: 1px solid var(--border-light);
    background: var(--bg-secondary);
}

.ai-input {
    width: 100%;
    padding: var(--space-2) var(--space-4);
    border: 1px solid var(--border-light);
    border-radius: 6px;
    font-size: 0.875rem;
    font-family: var(--font-sans);
}

.ai-input:focus {
    outline: none;
    border-color: var(--primary-500);
    box-shadow: 0 0 0 3px var(--primary-50);
}

.ai-form {
    display: flex;
    gap: var(--space-2);
}

.ai-send-btn {
    padding: var(--space-2) var(--space-4);
    background: var(--primary-500);
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: all 150ms ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.ai-send-btn:hover {
    background: var(--primary-600);
}

.ai-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999;
}

.ai-overlay.active {
    display: block;
}

/* Responsive */
@media (max-width: 768px) {
    .ai-assistant-widget {
        bottom: var(--space-4);
        right: var(--space-4);
    }
    
    .ai-panel {
        width: 100%;
        max-height: 70vh;
        right: 0;
        left: 0;
        bottom: 70px;
        border-radius: 12px 12px 0 0;
        margin: 0 var(--space-4);
        width: calc(100% - var(--space-8));
    }
    
    .ai-message p {
        max-width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const aiToggle = document.getElementById('ai-toggle');
    const aiPanel = document.getElementById('ai-panel');
    const aiClose = document.getElementById('ai-close');
    const aiOverlay = document.getElementById('ai-overlay');
    const aiForm = document.getElementById('ai-form');
    const aiInput = document.getElementById('ai-input');
    const aiMessages = document.getElementById('ai-messages');
    
    // Toggle panel
    aiToggle.addEventListener('click', function() {
        aiPanel.classList.toggle('active');
        aiToggle.classList.toggle('active');
        aiOverlay.classList.toggle('active');
        if (aiPanel.classList.contains('active')) {
            aiInput.focus();
        }
    });
    
    // Close panel
    aiClose.addEventListener('click', function() {
        aiPanel.classList.remove('active');
        aiToggle.classList.remove('active');
        aiOverlay.classList.remove('active');
    });
    
    aiOverlay.addEventListener('click', function() {
        aiPanel.classList.remove('active');
        aiToggle.classList.remove('active');
        aiOverlay.classList.remove('active');
    });
    
    // Send message
    aiForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const message = aiInput.value.trim();
        if (!message) return;
        
        // Add user message
        const userDiv = document.createElement('div');
        userDiv.className = 'ai-message ai-message-user';
        userDiv.innerHTML = `<p>${escapeHtml(message)}</p>`;
        aiMessages.appendChild(userDiv);
        
        aiInput.value = '';
        aiMessages.scrollTop = aiMessages.scrollHeight;
        
        // Send to backend
        fetch('/api/ai/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ message }),
        })
        .then(r => r.json())
        .then(data => {
            const botDiv = document.createElement('div');
            botDiv.className = 'ai-message ai-message-bot';
            botDiv.innerHTML = `<p>${escapeHtml(data.response)}</p>`;
            aiMessages.appendChild(botDiv);
            aiMessages.scrollTop = aiMessages.scrollHeight;
        })
        .catch(err => {
            console.error('AI error:', err);
            const errorDiv = document.createElement('div');
            errorDiv.className = 'ai-message ai-message-bot';
            errorDiv.innerHTML = `<p>Sorry, something went wrong. Please try again.</p>`;
            aiMessages.appendChild(errorDiv);
        });
    });
    
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }
});
</script>
```

---

## 7. PAGE REDESIGNS

### Projects List Page (Professional Card Design)

```blade
<!-- resources/views/projects/index.blade.php -->
@extends('layouts.app', [
    'pageTitle' => 'Projects',
    'pageDescription' => 'Manage and organize your projects',
    'pageActions' => '<x-button variant="primary" size="lg" icon="plus" href="' . route('projects.create') . '">New Project</x-button>'
])

@section('content')
<div class="projects-container">
    <!-- Search & Filters -->
    <div class="projects-filters">
        <input type="text" placeholder="Search projects..." class="search-input" id="project-search">
        
        <div class="filter-group">
            <select class="select-filter" id="status-filter">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="completed">Completed</option>
                <option value="archived">Archived</option>
            </select>
        </div>
    </div>
    
    <!-- Statistics Row -->
    <div class="statistics-grid">
        <x-stat-card 
            title="Total Projects" 
            value="{{ $totalProjects }}" 
            icon="folder"
            color="blue"
        />
        <x-stat-card 
            title="Active Projects" 
            value="{{ $activeProjects }}" 
            icon="play-fill"
            color="green"
        />
        <x-stat-card 
            title="Completed" 
            value="{{ $completedProjects }}" 
            icon="check-circle-fill"
            color="purple"
        />
        <x-stat-card 
            title="Team Members" 
            value="{{ $teamMembers }}" 
            icon="people-fill"
            color="orange"
        />
    </div>
    
    <!-- Projects Grid -->
    <div class="projects-grid">
        @forelse($projects as $project)
            <x-project-card :project="$project" />
        @empty
            <div class="empty-state">
                <i class="bi bi-folder-x"></i>
                <h3>No projects yet</h3>
                <p>Create your first project to get started</p>
                <x-button variant="primary" href="{{ route('projects.create') }}">Create Project</x-button>
            </div>
        @endforelse
    </div>
</div>

<style>
.projects-container {
    max-width: 1200px;
}

.projects-filters {
    display: flex;
    gap: var(--space-4);
    margin-bottom: var(--space-8);
}

.search-input {
    flex: 1;
    padding: var(--space-3) var(--space-4);
    border: 1px solid var(--border-light);
    border-radius: 8px;
    font-size: 0.875rem;
}

.filter-group {
    display: flex;
    gap: var(--space-4);
}

.select-filter {
    padding: var(--space-3) var(--space-4);
    border: 1px solid var(--border-light);
    border-radius: 8px;
    font-size: 0.875rem;
    background: white;
}

.statistics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--space-6);
    margin-bottom: var(--space-8);
}

.projects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: var(--space-6);
}

.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: var(--space-16);
    background: var(--bg-secondary);
    border-radius: 12px;
}

.empty-state i {
    font-size: 3rem;
    color: var(--text-tertiary);
    display: block;
    margin-bottom: var(--space-4);
}

@media (max-width: 768px) {
    .projects-filters {
        flex-direction: column;
    }
    
    .projects-grid {
        grid-template-columns: 1fr;
    }
    
    .statistics-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
@endsection
```

### Project Card Component

```blade
<!-- resources/views/components/project-card.blade.php -->
<div class="project-card">
    <!-- Header -->
    <div class="project-card-header">
        <div>
            <h3 class="project-title">{{ $project->name }}</h3>
            <p class="project-description">{{ Str::limit($project->description, 60) }}</p>
        </div>
        <div class="project-menu">
            <button class="project-menu-btn" onclick="toggleMenu(this)">
                <i class="bi bi-three-dots-vertical"></i>
            </button>
            <div class="project-menu-dropdown">
                <a href="{{ route('projects.edit', $project) }}" class="menu-item">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="{{ route('projects.show', $project) }}" class="menu-item">
                    <i class="bi bi-arrow-right"></i> View
                </a>
                <form method="POST" action="{{ route('projects.destroy', $project) }}" class="menu-item">
                    @csrf @method('DELETE')
                    <button type="submit" onclick="return confirm('Delete?')">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Progress -->
    <div class="project-progress-section">
        <div class="progress-header">
            <span class="progress-label">Progress</span>
            <span class="progress-percent">{{ $project->getCompletionPercent() }}%</span>
        </div>
        <div class="progress-bar">
            <div class="progress-fill" style="width: {{ $project->getCompletionPercent() }}%"></div>
        </div>
    </div>
    
    <!-- Stats -->
    <div class="project-stats">
        <div class="stat">
            <i class="bi bi-circle-fill" style="color: #ef4444;"></i>
            <span>{{ $project->openIssuesCount ?? 0 }} Open</span>
        </div>
        <div class="stat">
            <i class="bi bi-circle-fill" style="color: #10b981;"></i>
            <span>{{ $project->closedIssuesCount ?? 0 }} Closed</span>
        </div>
        <div class="stat">
            <i class="bi bi-hourglass-split"></i>
            <span>Due {{ $project->deadline?->format('M d') ?? 'N/A' }}</span>
        </div>
    </div>
    
    <!-- Health Indicator -->
    <div class="project-health">
        <span class="health-badge health-{{ $project->getHealth() }}">
            {{ ucfirst($project->getHealth()) }}
        </span>
    </div>
    
    <!-- Team Members -->
    <div class="project-team">
        <div class="team-avatars">
            @foreach($project->teamMembers->take(3) as $member)
                <img 
                    src="{{ $member->getAvatar() }}" 
                    alt="{{ $member->name }}"
                    class="avatar"
                    title="{{ $member->name }}"
                >
            @endforeach
            @if($project->teamMembers->count() > 3)
                <div class="avatar avatar-more" title="{{ $project->teamMembers->count() - 3 }} more">
                    +{{ $project->teamMembers->count() - 3 }}
                </div>
            @endif
        </div>
    </div>
    
    <!-- Footer -->
    <div class="project-footer">
        <a href="{{ route('projects.show', $project) }}" class="btn btn-ghost btn-sm">
            View Details →
        </a>
    </div>
</div>

<style>
.project-card {
    background: var(--bg-primary);
    border: 1px solid var(--border-light);
    border-radius: 12px;
    padding: var(--space-6);
    transition: all 200ms ease;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.project-card:hover {
    border-color: var(--border-medium);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
}

.project-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: var(--space-4);
    margin-bottom: var(--space-4);
}

.project-title {
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0 0 var(--space-2) 0;
    color: var(--text-primary);
}

.project-description {
    font-size: 0.875rem;
    color: var(--text-tertiary);
    margin: 0;
}

.project-menu {
    position: relative;
}

.project-menu-btn {
    width: 32px;
    height: 32px;
    border: none;
    background: transparent;
    cursor: pointer;
    border-radius: 6px;
    color: var(--text-secondary);
    transition: all 150ms ease;
}

.project-menu-btn:hover {
    background: var(--bg-secondary);
    color: var(--text-primary);
}

.project-menu-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border: 1px solid var(--border-light);
    border-radius: 8px;
    min-width: 150px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    display: none;
    z-index: 100;
}

.project-menu-dropdown.active {
    display: block;
}

.menu-item {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    padding: var(--space-3) var(--space-4);
    color: var(--text-primary);
    text-decoration: none;
    border: none;
    background: transparent;
    width: 100%;
    text-align: left;
    cursor: pointer;
    font-size: 0.875rem;
    transition: background 150ms ease;
}

.menu-item:hover {
    background: var(--bg-secondary);
}

.project-progress-section {
    margin-bottom: var(--space-4);
}

.progress-header {
    display: flex;
    justify-content: space-between;
    font-size: 0.875rem;
    margin-bottom: var(--space-2);
}

.progress-label {
    color: var(--text-secondary);
    font-weight: 500;
}

.progress-percent {
    color: var(--primary-600);
    font-weight: 600;
}

.progress-bar {
    width: 100%;
    height: 6px;
    background: var(--bg-secondary);
    border-radius: 3px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: var(--primary-500);
    transition: width 300ms ease;
}

.project-stats {
    display: flex;
    gap: var(--space-4);
    margin-bottom: var(--space-4);
    font-size: 0.875rem;
}

.stat {
    display: flex;
    align-items: center;
    gap: var(--space-2);
    color: var(--text-secondary);
}

.stat i {
    font-size: 0.75rem;
}

.project-health {
    margin-bottom: var(--space-4);
}

.health-badge {
    display: inline-block;
    padding: var(--space-1) var(--space-3);
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: capitalize;
}

.health-good { background: #dcfce7; color: #166534; }
.health-at-risk { background: #fef3c7; color: #92400e; }
.health-at-risk { background: #fee2e2; color: #991b1b; }

.project-team {
    margin-bottom: var(--space-4);
    flex: 1;
}

.team-avatars {
    display: flex;
    gap: -8px;
}

.avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--primary-100);
    border: 2px solid white;
    object-fit: cover;
    margin-left: -8px;
}

.avatar:first-child {
    margin-left: 0;
}

.avatar-more {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-secondary);
    background: var(--bg-secondary);
}

.project-footer {
    display: flex;
    gap: var(--space-4);
    margin-top: auto;
}
</style>
```

---

## 8. ISSUE CREATE PAGE REDESIGN

```blade
<!-- resources/views/issues/create.blade.php -->
@extends('layouts.app', [
    'pageTitle' => 'Create Issue',
    'pageDescription' => 'Create a new issue'
])

@section('content')
<div class="create-issue-container">
    <div class="create-form-wrapper">
        <form action="{{ route('issues.store') }}" method="POST" class="create-form">
            @csrf
            
            <!-- General Information Section -->
            <fieldset class="form-section">
                <legend class="section-title">General Information</legend>
                
                <x-form-group 
                    name="title"
                    label="Issue Title"
                    :required="true"
                    description="Be specific and descriptive"
                >
                    <input 
                        type="text"
                        name="title"
                        placeholder="e.g., Fix login button on mobile"
                        value="{{ old('title') }}"
                        maxlength="255"
                        class="form-input"
                    >
                </x-form-group>
                
                <x-form-group 
                    name="description"
                    label="Description"
                    description="Provide context and details"
                >
                    <textarea 
                        name="description"
                        placeholder="Describe the issue, steps to reproduce, expected behavior..."
                        rows="6"
                        class="form-input form-textarea"
                    >{{ old('description') }}</textarea>
                    <div class="textarea-hints">
                        <span class="hint">📝 Markdown supported</span>
                        <span class="hint">🔗 @mention users</span>
                    </div>
                </x-form-group>
            </fieldset>
            
            <!-- Classification Section -->
            <fieldset class="form-section">
                <legend class="section-title">Classification</legend>
                
                <div class="form-row">
                    <x-form-group 
                        name="status"
                        label="Status"
                        :required="true"
                        class="form-col"
                    >
                        <select name="status" class="form-input">
                            <option value="">Select status...</option>
                            @foreach(App\Models\Issue::getStatuses() as $value => $label)
                                <option value="{{ $value }}" {{ old('status') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </x-form-group>
                    
                    <x-form-group 
                        name="priority"
                        label="Priority"
                        :required="true"
                        class="form-col"
                    >
                        <select name="priority" class="form-input">
                            <option value="">Select priority...</option>
                            @foreach(App\Models\Issue::PRIORITIES as $priority)
                                <option value="{{ $priority }}" {{ old('priority') === $priority ? 'selected' : '' }}>
                                    {{ ucfirst($priority) }}
                                </option>
                            @endforeach
                        </select>
                    </x-form-group>
                </div>
            </fieldset>
            
            <!-- Project Details Section -->
            <fieldset class="form-section">
                <legend class="section-title">Project Details</legend>
                
                <x-form-group 
                    name="project_id"
                    label="Project"
                    :required="true"
                    description="Select which project this issue belongs to"
                >
                    <select name="project_id" class="form-input" id="project-select">
                        <option value="">Select project...</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                </x-form-group>
            </fieldset>
            
            <!-- Scheduling Section -->
            <fieldset class="form-section">
                <legend class="section-title">Scheduling</legend>
                
                <x-form-group 
                    name="due_date"
                    label="Due Date"
                    description="When should this be completed?"
                >
                    <input 
                        type="date"
                        name="due_date"
                        value="{{ old('due_date') }}"
                        class="form-input"
                    >
                </x-form-group>
            </fieldset>
            
            <!-- Organization Section -->
            <fieldset class="form-section">
                <legend class="section-title">Organization</legend>
                
                <x-form-group 
                    name="tags"
                    label="Tags"
                    description="Add labels to organize this issue"
                >
                    <div class="tag-input-wrapper">
                        <div class="tag-input-area" id="tag-input-area">
                            <input 
                                type="hidden"
                                name="tags"
                                id="tags-input"
                                value="{{ old('tags') }}"
                            >
                            <div class="tag-list" id="tag-list"></div>
                            <input 
                                type="text"
                                placeholder="Add tags..."
                                id="tag-search"
                                class="tag-search"
                            >
                        </div>
                        <div class="tag-suggestions" id="tag-suggestions"></div>
                    </div>
                </x-form-group>
                
                <x-form-group 
                    name="members"
                    label="Assignees"
                    description="Who will work on this?"
                >
                    <select name="members[]" multiple class="form-input" id="members-select">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </x-form-group>
            </fieldset>
            
            <!-- Form Actions -->
            <div class="form-actions">
                <x-button type="submit" variant="primary" size="lg">
                    Create Issue
                </x-button>
                <x-button type="button" variant="secondary" onclick="window.history.back()">
                    Cancel
                </x-button>
            </div>
        </form>
    </div>
    
    <!-- AI Assistant Panel -->
    <div class="create-ai-panel">
        <h3>✨ AI Assistant</h3>
        <p>Let AI help you create the perfect issue</p>
        
        <div class="ai-suggestions">
            <div class="ai-suggestion" onclick="useSuggestion('priority')">
                <i class="bi bi-sparkles"></i>
                <span>Suggest Priority</span>
            </div>
            <div class="ai-suggestion" onclick="useSuggestion('title')">
                <i class="bi bi-sparkles"></i>
                <span>Generate Title</span>
            </div>
            <div class="ai-suggestion" onclick="useSuggestion('tags')">
                <i class="bi bi-sparkles"></i>
                <span>Suggest Tags</span>
            </div>
        </div>
    </div>
</div>

<style>
.create-issue-container {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: var(--space-8);
    max-width: 1000px;
}

.create-form-wrapper {
    background: var(--bg-primary);
    border: 1px solid var(--border-light);
    border-radius: 12px;
    padding: var(--space-8);
}

.create-form {
    display: flex;
    flex-direction: column;
    gap: var(--space-8);
}

.form-section {
    border: none;
    padding: 0;
    margin: 0;
}

.form-section:not(:last-of-type) {
    padding-bottom: var(--space-8);
    border-bottom: 1px solid var(--border-light);
}

.section-title {
    display: block;
    font-size: 0.875rem;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--text-tertiary);
    margin-bottom: var(--space-4);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--space-6);
}

.form-input {
    width: 100%;
    padding: var(--space-3) var(--space-4);
    border: 1px solid var(--border-light);
    border-radius: 8px;
    font-family: var(--font-sans);
    font-size: 1rem;
    transition: all 200ms ease;
}

.form-input:focus {
    outline: none;
    border-color: var(--primary-500);
    box-shadow: 0 0 0 3px var(--primary-50);
}

.form-textarea {
    resize: vertical;
    min-height: 120px;
    font-family: var(--font-mono);
}

.textarea-hints {
    display: flex;
    gap: var(--space-4);
    margin-top: var(--space-2);
    font-size: 0.75rem;
}

.hint {
    color: var(--text-tertiary);
}

.form-actions {
    display: flex;
    gap: var(--space-4);
    margin-top: var(--space-4);
}

.create-ai-panel {
    background: linear-gradient(135deg, var(--primary-50) 0%, var(--primary-100) 100%);
    border: 1px solid var(--primary-200);
    border-radius: 12px;
    padding: var(--space-6);
    height: fit-content;
    position: sticky;
    top: 100px;
}

.create-ai-panel h3 {
    font-size: 1rem;
    font-weight: 600;
    margin: 0 0 var(--space-2) 0;
    color: var(--primary-700);
}

.create-ai-panel > p {
    font-size: 0.875rem;
    color: var(--primary-600);
    margin: 0 0 var(--space-4) 0;
}

.ai-suggestions {
    display: flex;
    flex-direction: column;
    gap: var(--space-2);
}

.ai-suggestion {
    display: flex;
    align-items: center;
    gap: var(--space-2);
    padding: var(--space-3) var(--space-4);
    background: white;
    border-radius: 8px;
    cursor: pointer;
    transition: all 150ms ease;
    border: 1px solid var(--primary-200);
}

.ai-suggestion:hover {
    background: var(--primary-100);
    border-color: var(--primary-300);
    box-shadow: 0 2px 8px rgba(14, 165, 233, 0.1);
}

.ai-suggestion i {
    color: var(--primary-500);
}

.ai-suggestion span {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--primary-700);
}

@media (max-width: 968px) {
    .create-issue-container {
        grid-template-columns: 1fr;
    }
    
    .create-ai-panel {
        position: static;
    }
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>
@endsection
```

---

## CONTINUATION

This design system is substantial. Continue to the IMPLEMENTATION section for:

- Tags page redesign
- Button standardization across all pages
- Mobile responsive patterns
- Dark mode implementation
- AI Assistant API endpoints
- Complete page-by-page conversion

Would you like me to continue with the implementation guide for all pages?
