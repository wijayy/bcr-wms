@php
    $title = $shipments ? "Edit Shipment $shipments->name" : __('Create New Supplier');
@endphp

<x-app-layout title='{{ $title }}' back="">
    <form enctype="multipart/form-data"
        action="{{ $shipments ? route('shipments.update', ['shipment' => $shipments->slug]) : route('shipments.store') }}"
        method="post">
        @csrf
        @if ($shipments ?? false)
            @method('put')
        @endif
        <div class="grid grid-cols-1 gap-4 mt-4 lg:grid-cols-2">
            <div>
                <x-input-label for="name" :value="__('name')" />
                <x-text-input id="name" class="block w-full mt-1" type="text" name="name" :value="old('name', $shipments->name ?? '')"
                    required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="marketing_id" :value="__('marketing')" />
                <x-select-input id="marketing_id" name="marketing_id">
                    @if (Auth::user()->is_admin)
                        @foreach ($users as $item)
                            <x-select-option :selected="$item->id == old('marketing_id', $shipments->marketing_id ?? '')"
                                value="{{ $item->id }}">{{ $item->name }}</x-select-option>
                        @endforeach
                    @else
                        <x-select-option :selected="true"
                            value="{{ Auth::user()->id }}">{{ Auth::user()->name }}</x-select-option>
                    @endif
                </x-select-input>
                <x-input-error :messages="$errors->get('marketing_id')" class="mt-2" />
            </div>

        </div>
        <div class="flex justify-center mt-4">
            <x-primary-a onclick="event.preventDefault();
        this.closest('form').submit();">Submit</x-primary-a>
        </div>
    </form>
</x-app-layout>
