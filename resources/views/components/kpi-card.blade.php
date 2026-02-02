<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-gray-500 text-sm mb-1">{{ $title }}</p>
            <p class="text-3xl font-bold text-gray-800">{{ $value }}</p>
        </div>
        <div class="w-16 h-16 bg-{{ $color }}-100 rounded-full flex items-center justify-center">
            <i class="fas fa-{{ $icon }} text-{{ $color }}-500 text-2xl"></i>
        </div>
    </div>
</div>