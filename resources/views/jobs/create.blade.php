@php
    $title = $job ? "Edit $job->no_job`s Data" : "Add New $shipment->name`s Jobs";
@endphp

<x-app-layout title="{{ $title }}" back="{{ route('jobs.index', ['shipment' => $shipment->slug]) }}">
    <form
        action="{{ $job ? route('jobs.update', ['job' => $job->slug, 'shipment' => $shipment->slug]) : route('jobs.store', ['shipment' => $shipment->slug]) }}"
        method="post" class="">
        @csrf
        @if ($job)
            @method('put')
        @endif
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            <div>
                <x-input-label for="no_job" :value="__('No Job')" />
                <x-text-input id="no_job" class="block w-full mt-1" type="text" name="no_job" :value="old('no_job', $job->no_job ?? '')"
                    required autocomplete="no_job" />
                <x-input-error :messages="$errors->get('no_job')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="destination" :value="__('Destination')" />
                <x-text-input id="destination" class="block w-full mt-1" type="text" name="destination"
                    :value="old('destination', $job->destination ?? '')" required autocomplete="destination" />
                <x-input-error :messages="$errors->get('destination')" class="mt-2" />
            </div>
            {{-- @dd($job->shipping_date->format('Y-m-d')) --}}
            <div>
                <x-input-label for="shipping_date" :value="__('Stuffing')" />
                <x-text-input id="shipping_date" class="block w-full mt-1" type="date" name="shipping_date"
                    :value="old(
                        'shipping_date',
                        $job->shipping_date ?? false ? $job->shipping_date->format('Y-m-d') : '',
                    )" required autocomplete="shipping_date" />
                <x-input-error :messages="$errors->get('shipping_date')" class="mt-2" />
            </div>
        </div>



        <div class="flex justify-center mt-4">
            <x-primary-a onclick="event.preventDefault();
        this.closest('form').submit();">Submit</x-primary-a>
        </div>
    </form>
</x-app-layout>
