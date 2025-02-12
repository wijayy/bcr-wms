<x-app-layout title="Export Data to Excel">
    <div class="grid min-h-full grid-cols-1 gap-4 md:grid-cols-4">
        <form method="post" action="{{ route('export.goods') }}"
            class="flex flex-col justify-between w-full p-4 aspect-square shadow-mine rounded-xl">
            @csrf
            <div class="w-full text-lg text-center lg:text-2xl">Export Goods</div>

            <div class="flex justify-center mt-4">
                <x-primary-a
                    onclick="event.preventDefault();
            this.closest('form').submit();">Export</x-primary-a>
            </div>

        </form>
        @php
            $months = [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December',
            ];
        @endphp
        <form method="post" action="{{ route('export.stock') }}"
            class="flex flex-col justify-between w-full p-4 aspect-square shadow-mine rounded-xl">
            @csrf
            <div class="w-full text-lg text-center lg:text-2xl">Export Stock</div>

            <div class="grid grid-cols-2 ">
                <div>
                    <x-input-label for="month" :value="__('month')" />
                    <x-select-input id="month" name="month">
                        @foreach ($months as $index => $item)
                            <x-select-option value="{{ $index + 1 }}">{{ $item }}</x-select-option>
                        @endforeach
                    </x-select-input>
                    <x-input-error :messages="$errors->get('month')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="year" :value="__('year')" />
                    <x-select-input id="year" name="year">
                        @for ($year = date('Y'); $year >= 2025; $year--)
                            <x-select-option value="{{ $year }}">{{ $year }}</x-select-option>
                        @endfor

                    </x-select-input>
                    <x-input-error :messages="$errors->get('year')" class="mt-2" />
                </div>
            </div>

            <div class="flex justify-center mt-4">
                <x-primary-a
                    onclick="event.preventDefault();
            this.closest('form').submit();">Export</x-primary-a>
            </div>

        </form>
    </div>
</x-app-layout>
