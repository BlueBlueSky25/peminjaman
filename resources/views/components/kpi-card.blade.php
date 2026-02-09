@props(['title', 'value', 'icon', 'color' => 'blue'])

@php
    $colors = [
        'blue' => 'bg-blue-100',
        'green' => 'bg-green-100',
        'yellow' => 'bg-yellow-100',
        'purple' => 'bg-purple-100',
        'red' => 'bg-red-100',
    ];
    
    $iconColors = [
        'blue' => 'text-blue-500',
        'green' => 'text-green-500',
        'yellow' => 'text-yellow-500',
        'purple' => 'text-purple-500',
        'red' => 'text-red-500',
    ];
    
    $bgColor = $colors[$color] ?? $colors['blue'];
    $iconColor = $iconColors[$color] ?? $iconColors['blue'];
@endphp

<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-600 mb-1">{{ $title }}</p>
            <p class="text-3xl font-bold text-gray-800">{{ $value }}</p>
        </div>
        <div class="w-16 h-16 {{ $bgColor }} rounded-full flex items-center justify-center">
            <i class="fas {{ $icon }} text-2xl {{ $iconColor }}"></i>
        </div>
    </div>
</div>