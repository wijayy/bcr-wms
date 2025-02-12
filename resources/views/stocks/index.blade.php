@php
    $type = ['stock', 'returned', 'supplier'];
@endphp

<x-app-layout title="Add New {{ $goods->code }}-{{ $goods->name }}`s Stock Record"
    back="{{ route('goods.show', ['shipment' => $shipment->slug, 'supplier' => $supplier->slug, 'good' => $goods->slug]) }}">
    <form enctype="multipart/form-data"
        action="{{ route('stocks.store', ['good' => $goods->slug, 'shipment' => $shipment->slug, 'supplier' => $supplier->slug]) }}"
        method="post">
        @csrf
        <div class="flex flex-wrap justify-start gap-4 lg:flex-no-wrap" x-data="">
            <div class="">
                <x-input-label for="note" :value="__('Goods receipt note')" />
                <div class="relative flex mt-1 text-center rounded-md shadow-md size-40 aspect-square"
                    x-data="{
                        image: '',
                        text: 'Goods receipt note',
                        imagePreview() {
                            return URL.createObjectURL(event.target.files[0]);
                        }
                    }">
                    <img :src="image" :alt="'Gambar'" class="z-10 object-cover rounded-md size-full"
                        x-show="image">
                    <input type="file" id="note" name="note" @change="image=imagePreview()" class="sr-only">
                    <label for="note" :class="{ 'opacity-100': !image, 'opacity-0': image }"
                        class="absolute top-0 left-0 z-20 flex items-center justify-center w-full h-full bg-transparent border border-black border-dashed rounded-md cursor-pointer ALIGN text-sky-500 hover:text-blue-700"
                        x-text="text"></label>
                </div>
                <x-input-error :messages="$errors->get('note')" class="mt-2" />
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 mt-4 lg:grid-cols-2">
            <div>
                <x-input-label for="amount" :value='__("amount (Stocks $goods->stock $goods->unit)")' />
                <x-text-input id="amount" x-model="amount" @input="validateAmount" class="block w-full mt-1"
                    type="number" name="amount" min="0" :value="old('amount')" required autocomplete="amount" />
                <x-input-error x-show="error" x-text="error" :messages="$errors->get('amount')" class="mt-2" />
            </div>
            <div class="">
                <x-input-label for="type" :value="__('type')" />
                <x-select-input id="type" x-model="type" name="type" class="" @change="validateAmount">
                    @foreach ($type as $item)
                        <x-select-option :selected="$item == old('type')"
                            value="{{ $item }}">{{ $item }}</x-select-option>
                    @endforeach
                </x-select-input>
                <x-input-error :messages="$errors->get('type')" class="mt-2" />
            </div>
        </div>

        <div class="flex justify-center mt-4">
            <x-primary-a onclick="event.preventDefault();
        this.closest('form').submit();">Submit</x-primary-a>
        </div>
    </form>
</x-app-layout>
