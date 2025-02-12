@props(['disabled' => false])

<input @disabled($disabled)
    {{ $attributes->merge(['class' => 'border border-mine-200 p-1 focus:ring-mine-400 rounded-md shadow-mine']) }}>
