@if (session('status'))
    <div class="flash-notice">
        {{ session('status') }}
    </div>
@endif
