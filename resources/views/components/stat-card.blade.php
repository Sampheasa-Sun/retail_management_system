@props([
    'icon' => 'fas fa-question-circle',
    'value' => 0,
    'label' => 'Label',
    'color' => 'gray'
])

@php
$colorClasses = [
    'gray'    => 'bg-gray-500',
    'indigo'  => 'bg-indigo-500',
    'green'   => 'bg-green-500',
    'yellow'  => 'bg-yellow-500',
    'red'     => 'bg-red-500',
];

$bgColorClass = $colorClasses[$color] ?? $colorClasses['gray'];
@endphp

<div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-md flex items-center gap-4 hover:shadow-lg transition-shadow duration-300">
    <div class="w-14 h-14 rounded-full flex items-center justify-center text-white text-2xl {{ $bgColorClass }}">
        <i class="{{ $icon }}"></i>
    </div>
    <div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $value }}</div>
        <div class="text-md font-medium text-gray-500 dark:text-gray-400">{{ $label }}</div>
    </div>
</div>