@props(['icon' => 'fa-ticket', 'title' => 'Title', 'count' => 0, 'color' => '#0B0F3B'])
<div class="stat-card"
    style="border-radius:12px; background: #fff; padding:18px; box-shadow: 0 6px 18px rgba(11,15,59,0.08);">
    <div class="d-flex align-items-center gap-3">
        <div class="stat-icon"
            style="width:56px;height:56px;border-radius:10px;background:linear-gradient(180deg, rgba(11,15,59,0.08), rgba(11,15,59,0.03));display:flex;align-items:center;justify-content:center;color: #0B0F3B;font-size:20px;">
            <i class="fa {{ $icon }}"></i>
        </div>
        <div>
            <div class="text-muted" style="font-size:12px">{{ $title }}</div>
            <div class="fw-bold" style="font-size:20px">{{ $count }}</div>
        </div>
    </div>
</div>
<style>
    .stat-card:hover {
        transform: translateY(-4px);
        transition: all .18s ease;
        box-shadow: 0 12px 28px rgba(11, 15, 59, 0.12);
    }
</style>