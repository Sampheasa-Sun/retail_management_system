
<div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg']) }}>
    <div class="p-6">
        {{ $slot }}
    </div>
</div>