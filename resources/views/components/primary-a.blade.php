@php
    $classes = 'px-2 py-1 bg-mine-200 hover:bg-mine-300 shadow-mine text-lg rounded-lg text-white transition cursor-pointer';
@endphp


<a {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
