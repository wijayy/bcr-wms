@props(['active', 'value' => ''])

@php
    $classes =
        $active ?? false
            ? 'relative flex justify-center items-center stroke-white text-white size-10 rounded-xl bg-mine-300 group/item transition-all duration-300 '
            : 'relative flex justify-center items-center stroke-white text-white size-10 rounded-xl bg-mine-200 group/item transition-all duration-300 hover:bg-mine-300';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
    <div
        class="absolute px-3 py-2 text-sm text-white transition-all duration-300 translate-x-4 -translate-y-1/2 opacity-0 pointer-events-none font-comfortaa cursor-none rounded-xl bg-mine-300 top-1/2 left-full group-hover/item:opacity-100">
        {{ $value }}
    </div>
</a>
