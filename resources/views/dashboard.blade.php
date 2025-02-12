<x-app-layout title="Dashboard">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5">
        @if (Auth::user()->is_admin)
            <a href="{{ route('marketing.index') }}"
                class="flex flex-col items-center justify-center w-full gap-2 aspect-video rounded-xl bg-mine-100 shadow-mine">
                <div class="w-full text-6xl text-center">{{ $marketing->count() }}</div>
                <div class="w-full text-2xl text-center ">Marketing</div>
            </a>
        @endif
        <a href="{{ route('shipments.index') }}"
            class="flex flex-col items-center justify-center w-full gap-2 aspect-video rounded-xl bg-mine-100 shadow-mine">
            <div class="w-full text-6xl text-center">{{ $shipment->count() }}</div>
            <div class="w-full text-2xl text-center ">Total Shipment</div>
        </a>

        <div
            class="flex flex-col items-center justify-center w-full gap-2 aspect-video rounded-xl bg-mine-100 shadow-mine">
            <div class="w-full text-6xl text-center">{{ $goods->sum('stock') }}</div>
            <div class="w-full text-2xl text-center ">Goods in Stock</div>
        </div>
        <div
            class="flex flex-col items-center justify-center w-full gap-2 aspect-video rounded-xl bg-mine-100 shadow-mine">
            <div class="w-full text-6xl text-center">{{ $stocks->where('type', 'returned')->sum('amount') }}</div>
            <div class="w-full text-2xl text-center ">Goods Returned</div>
        </div>
        @if (Auth::user()->is_admin)
            <form action="{{ route('convertionrate.update', ['convertionrate' => $convertionrate->slug]) }}"
                class="flex flex-col justify-center gap-2 p-2 bg-mine-100 rounded-xl aspect-video shadow-mine"
                method="post">
                @csrf
                @method('put')
                <div class="w-full text-2xl text-center">Convertion Rate $ to IDR</div>
                <div class="px-4">
                    <x-input-label for="rate" :value="__('Rate')" />
                    <x-text-input id="rate" class="block w-full mt-1" type="text" name="rate"
                        :value="old('rate', $convertionrate->rate ?? '')" required autofocus autocomplete="rate" />
                    <x-input-error :messages="$errors->get('rate')" class="mt-2" />
                </div>
                <div class="flex justify-center mt-4">
                    <x-primary-a
                        onclick="event.preventDefault();
            this.closest('form').submit();">Submit</x-primary-a>
                </div>
            </form>
        @endif

    </div>
</x-app-layout>
