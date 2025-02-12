@props(['disable' => false])

<select @disabled($disable)
    {{ $attributes->merge(['class' => 'mt-1 p-1 border border-mine-200 focus:border-indigo-500 w-full focus:ring-mine-300 rounded-md shadow-sm']) }}>
    {{ $slot }}
</select>
