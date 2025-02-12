@php
    $title = $supplier ? "Edit $supplier->name" : __('Create New Supplier');
@endphp

<x-app-layout title='{{ $title }}' back="{{ route('suppliers.index', ['shipment' => $shipment->slug]) }}">
    <form enctype="multipart/form-data"
        action="{{ $supplier ? route('suppliers.update', ['supplier' => $supplier->slug, 'shipment' => $shipment->slug]) : route('suppliers.store', ['shipment' => $shipment->slug]) }}"
        method="post">
        @csrf
        @if ($supplier ?? false)
            @method('put')
        @endif

        <div class="grid grid-cols-1 gap-4 mt-4 lg:grid-cols-3">
            <div>
                <x-input-label for="name" :value="__('name')" />
                <x-text-input id="name" class="block w-full mt-1" type="text" name="name" :value="old('name', $supplier->name ?? '')"
                    required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="phone" :value="__('phone')" />
                <x-text-input id="phone" class="block w-full mt-1" type="text" name="phone" :value="old('phone', $supplier->phone ?? '')"
                    required autocomplete="phone" />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <div class="">
                <x-input-label for="address" :value="__('address')" />
                <x-text-input id="address" class="block w-full mt-1" type="text" name="address" :value="old('address', $supplier->address ?? '')"
                    required autocomplete="address" />
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>
        </div>
        <div class="flex justify-center mt-4">
            <x-primary-a onclick="event.preventDefault();
        this.closest('form').submit();">Submit</x-primary-a>
        </div>
    </form>


    <script>
        document.getElementById('phone').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, ''); // Hanya izinkan angka
        });
    </script>
</x-app-layout>
