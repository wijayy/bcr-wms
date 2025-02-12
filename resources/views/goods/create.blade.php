@php
    $title = $goods
        ? "Edit Goods $goods->code"
        : "Add New Goods for Shipment $shipment->name at Supplier $supplier->name ";
    $unit = ['pcs', 'set', 'unit', 'prs'];
@endphp

<x-app-layout title='{{ $title }}'
    back="{{ route('goods.index', ['shipment' => $shipment->slug, 'supplier' => $supplier->slug]) }}">
    <form enctype="multipart/form-data"
        action="{{ $goods ? route('goods.update', ['good' => $goods->slug, 'shipment' => $shipment->slug, 'supplier' => $supplier->slug]) : route('goods.store', ['shipment' => $shipment->slug, 'supplier' => $supplier->slug]) }}"
        method="post">
        @csrf
        @if ($goods ?? false)
            @method('put')
        @endif
        <div class="flex flex-wrap justify-start gap-4 lg:flex-no-wrap" x-data="">

            <div class="">
                <x-input-label for="image" :value="__('Picture of the Goods')" />
                <div class="relative flex mt-1 text-center rounded-md shadow-md size-40 aspect-square"
                    x-data="{
                        image: '{{ $goods ? asset("storage/$goods->image") : '' }}',
                        text: 'Picture of the Goods',
                        imagePreview() {
                            return URL.createObjectURL(event.target.files[0]);
                        }
                    }">
                    <img :src="image" alt="image" class="z-10 object-cover rounded-md size-full"
                        x-show="image">
                    <input type="file" id="image" name="image" @change="image=imagePreview()" class="sr-only">
                    <label for="image" :class="{ 'opacity-100': !image, 'opacity-0': image }"
                        class="absolute top-0 left-0 z-20 flex items-center justify-center w-full h-full bg-transparent border border-black border-dashed rounded-md cursor-pointer ALIGN text-sky-500 hover:text-blue-700"
                        x-text="text"></label>
                </div>
                <x-input-error :messages="$errors->get('image')" class="mt-2" />
            </div>
            @if (!isset($goods))
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
                        <img :src="image" alt="note" class="z-10 object-cover rounded-md size-full"
                            x-show="image">
                        <input type="file" id="note" name="note" @change="image=imagePreview()"
                            class="sr-only" required>
                        <label for="note" :class="{ 'opacity-100': !image, 'opacity-0': image }"
                            class="absolute top-0 left-0 z-20 flex items-center justify-center w-full h-full bg-transparent border border-black border-dashed rounded-md cursor-pointer ALIGN text-sky-500 hover:text-blue-700"
                            x-text="text"></label>
                    </div>
                    <x-input-error :messages="$errors->get('note')" class="mt-2" />
                </div>
            @endif
        </div>
        <div class="grid grid-cols-1 gap-4 mt-4 lg:grid-cols-3">

            <div>
                <x-input-label for="code" :value="__('code')" />
                <x-text-input id="code" class="block w-full mt-1" type="text" name="code" :value="old('code', $goods->code ?? '')"
                    required autocomplete="code" />
                <x-input-error :messages="$errors->get('code')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="desc" :value="__('Description of the goods')" />
                <x-text-input id="desc" class="block w-full mt-1" type="text" name="desc" :value="old('desc', $goods->desc ?? '')"
                    required autocomplete="desc" />
                <x-input-error :messages="$errors->get('desc')" class="mt-2" />
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2">
                <div>
                    <x-input-label for="material" :value="__('material')" />
                    <x-text-input id="material" class="block w-full mt-1 lg:rounded-r-none" type="text"
                        name="material" :value="old('material', $goods->material ?? '')" autocomplete="material" />
                    <x-input-error :messages="$errors->get('material')" class="mt-2" />
                </div>
                <div class="">
                    <x-input-label for="type" :value="__('Type')" />
                    <x-select-input required id="type" name="type" :disable="$goods ?? false"
                        class="lg:rounded-l-none ">
                        <x-select-option value="stock">Stock</x-select-option>
                        <x-select-option value="supplier">Supplier</x-select-option>
                    </x-select-input>
                    <x-input-error :messages="$errors->get('type')" class="mt-2" />
                </div>
            </div>
            <div class="flex items-start">
                <div class="w-4/5">
                    <x-input-label for="stock" :value="__('stock')" />
                    <x-text-input id="stock" class="block w-full mt-1 -ml-px rounded-r-none" type="number"
                        name="stock" :value="old('stock', $goods->stock ?? '')" required :disabled="$goods ?? false" autocomplete="stock" />
                    <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                </div>
                <div class="w-1/5">
                    <x-input-label for="unit" :value="__('unit')" />
                    <x-select-input id="unit" name="unit" class="rounded-l-none ">
                        @foreach ($unit as $item)
                            <x-select-option :selected="$item == old('unit', $goods->unit ?? '')"
                                value="{{ $item }}">{{ $item }}</x-select-option>
                        @endforeach
                    </x-select-input>
                    <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                </div>
            </div>
            <div>
                <x-input-label for="weight" :value="__('net weight (Kg/Pcs)')" />
                <x-text-input id="weight" class="block w-full mt-1 rounded-r-none" type="number" name="weight"
                    :value="old('weight', $goods->weight ?? '')" autocomplete="weight" />
                <x-input-error :messages="$errors->get('weight')" class="mt-2" />
            </div>
            <div class="flex w-full">
                <div class="w-1/2">
                    <x-input-label for="us_price" :value="__('$ price')" />
                    <x-text-input id="us_price" class="block w-full mt-1 rounded-r-none" type="number" step="0.01"
                        name="us_price" :value="old('us_price', $goods->us_price ?? '')" autocomplete="us_price" />
                    <x-input-error :messages="$errors->get('us_price')" class="mt-2" />
                </div>
                <div class="w-1/2">
                    <x-input-label for="id_price" :value="__('IDR price')" />
                    <x-text-input id="id_price" class="block w-full mt-1 rounded-l-none" type="number"
                        name="id_price" :value="old('id_price', $goods->id_price ?? '')" autocomplete="id_price" />
                    <x-input-error :messages="$errors->get('id_price')" class="mt-2" />
                </div>
            </div>
        </div>

        <div class="flex justify-center mt-4">
            <x-primary-a onclick="event.preventDefault();
        this.closest('form').submit();">Submit</x-primary-a>
        </div>
    </form>
</x-app-layout>
