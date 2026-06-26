@if (session('status'))
    <div class="flash-notice">
        {{ session('status') }}
    </div>
@endif

@if (session('success'))
    <div style="background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px; padding: 12px 16px; margin-bottom: 16px; color: #155724; font-size: 14px; animation: slideIn 0.3s ease;">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
    </div>
    <style>
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endif
