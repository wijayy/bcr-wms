@props(['selected' => false])

<option @selected($selected)
    {{ $attributes->merge(['class' => 'w-full bg-white hover:text-mine-200 focus:text-mine-400 checked:bg-mine-200 checked:text-white']) }}>
    {{ $slot }}</option>
