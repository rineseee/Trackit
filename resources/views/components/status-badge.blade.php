@props(['status'])
@php
    $map = [
        'open' => 'bg-danger text-white',
        'in_progress' => 'bg-warning text-dark',
        'pending' => 'bg-primary text-white',
        'resolved' => 'bg-success text-white',
        'closed' => 'bg-secondary text-white',
        'canceled' => 'bg-danger text-white'
    ];
@endphp
<span class="badge {{ $map[$status] ?? 'bg-secondary text-white' }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>