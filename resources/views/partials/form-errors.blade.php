@if ($errors->any())
    <div class="flash-error">
        <p style="margin: 0 0 8px; font-weight: 600;">Please fix the highlighted fields.</p>
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
