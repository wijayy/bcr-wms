@php
    $title = $shipment ? "All $shipment->name`s Jobs" : 'All Jobs';
@endphp

<x-app-layout title="{{ $title }}" back="{{ route('shipments.index') }}">
    <div class="flex justify-between">
        <div class="w-1/2">
            <x-searchbar></x-searchbar>
        </div>
        {{-- @dd($jobs->where('status', 0)) --}}
        @if ($jobs->where('status', 0)->isEmpty())
            <x-primary-a href="{{ route('jobs.create', ['shipment' => $shipment->slug]) }}">
                Add New Jobs
            </x-primary-a>
        @endif
    </div>

    <div class="grid grid-cols-5 gap-4 mt-4">
        @foreach ($jobs as $item)
            {{-- @dd($item->shipment->name) --}}
            <div class="flex flex-col justify-between p-2 space-y-2 bg-white min-h-max rounded-xl shadow-mine">
                <div class="flex items-center justify-center w-full gap-2 text-center">
                    <div class="text-xl">{{ $item->no_job }}</div>
                    @if ($item->status)
                        <div class="px-2 py-1 text-xs text-white rounded-lg bg-mine-200">Done</div>
                    @else
                        <div class="px-2 py-1 text-xs text-white rounded-lg bg-mine-400">Progres</div>
                    @endif
                </div>
                <div class="">Shipment : {{ $item->shipment ? $item->shipment->name : 'No Shipment Assigned' }}
                </div>
                {{-- <div class="">Marketing : {{ $item->supplier->user->name }}</div> --}}
                {{-- <div class="">Unique Goods : {{ $item->job_detail->count() }}</div>
                <div class="">Total Goods : {{ $item->job_detail->sum('amount') }}</div> --}}
                <div class="">Destination : {{ $item->destination }}</div>
                <div class="">Stuffing : {{ $item->shipping_date->format('Y-m-d') }}</div>
                <div class="">Total Boxes : {{ $item->box->count() }}</div>
                <div class="">Total Goods in Depart :
                    {{ $item->box_detail->where('amount', '>', 0)->sum('amount') }}</div>
                <div class="flex justify-center gap-2 mt-2 ">
                    <x-action-set :showLabel="'Boxes'" :show="route('boxes.index', [
                        'job' => $item->slug,
                        'shipment' => $shipment->slug,
                    ])" :edit="!$item->status
                        ? route('jobs.edit', [
                            'job' => $item->slug,
                            'shipment' => $shipment->slug,
                        ])
                        : null"
                        :delete="!$item->status
                            ? route('jobs.destroy', [
                                'job' => $item->slug,
                                'shipment' => $shipment->slug,
                            ])
                            : null"></x-action-set>
                    @if (!$item->status)
                        <form action="{{ route('job.done', ['job' => $item->slug]) }}" method="post">
                            @csrf
                            <x-action-a class="text-white cursor-pointer bg-mine-300"
                                onclick="event.preventDefault();
                            this.closest('form').submit();">
                                <x-action-label>Mark as Done</x-action-label>
                                <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="#fff" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                    </g>
                                </svg>
                            </x-action-a>
                        </form>
                    @endif

                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
