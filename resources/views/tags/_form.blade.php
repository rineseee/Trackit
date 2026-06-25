<form
    method="POST"
    action="{{ route('tags.store') }}"
    style="display: flex; flex-direction: column; gap: 12px; border-radius: 10px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); padding: 12px 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);"
>
    @csrf

    @include('partials.form-errors')

    <div style="border-radius: 8px; background: var(--trackit-surface-soft); padding: 12px; border: 1px solid var(--trackit-border);">
        <p style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--trackit-muted); margin: 0;">Tag setup</p>
        <h2 style="font-size: 15px; font-weight: 700; color: var(--trackit-text); margin: 4px 0 0; letter-spacing: -0.01em;">Create a reusable label</h2>
        <p style="font-size: 12px; color: var(--trackit-muted); margin: 4px 0 0; line-height: 1.5;">
            Use short labels to organize work.
        </p>
    </div>

    <div style="display: flex; flex-direction: column; gap: 10px;">
        <label style="display: block;">
            <span style="display: block; margin-bottom: 4px; font-size: 12px; font-weight: 600; color: var(--trackit-text);">Tag name</span>
            <input
                type="text"
                name="name"
                value="{{ old('name', $tag->name) }}"
                style="width: 100%; border-radius: 6px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); color: var(--trackit-text); padding: 8px 10px; font-size: 12px; outline: none; transition: all 150ms ease;"
                placeholder="backend"
            >
        </label>

        <label style="display: block;">
            <span style="display: block; margin-bottom: 4px; font-size: 12px; font-weight: 600; color: var(--trackit-text);">Color</span>
            <input
                type="text"
                name="color"
                value="{{ old('color', $tag->color) }}"
                style="width: 100%; border-radius: 6px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); color: var(--trackit-text); padding: 8px 10px; font-size: 12px; outline: none; transition: all 150ms ease;"
                placeholder="#0ea5e9"
            >
            <span style="display: block; margin-top: 4px; font-size: 11px; color: var(--trackit-muted); line-height: 1.4;">
                Hex color or label
            </span>
        </label>
    </div>

    <div style="display: flex; flex-wrap: wrap; gap: 8px; padding-top: 4px;">
        <button type="submit" style="display: inline-flex; align-items: center; gap: 6px; border-radius: 6px; background: var(--trackit-primary); color: white; padding: 8px 14px; font-size: 12px; font-weight: 600; border: none; cursor: pointer; transition: all 150ms ease;">
            <i class="bi bi-check2" style="font-size: 13px;"></i>
            Create tag
        </button>
        <a href="{{ route('tags.index') }}" style="display: inline-flex; align-items: center; gap: 6px; border-radius: 6px; background: var(--trackit-surface-soft); padding: 8px 14px; font-size: 12px; font-weight: 600; color: var(--trackit-text); text-decoration: none; transition: all 150ms ease; border: 1px solid var(--trackit-border);">
            <i class="bi bi-x-lg" style="font-size: 13px;"></i>
            Cancel
        </a>
    </div>
</form>
