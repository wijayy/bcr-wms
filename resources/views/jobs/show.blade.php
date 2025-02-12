<x-app-layout title="Detail Job {{ $job->no_job }}">
    @php
        $total = 0;
    @endphp
    <div class="flex justify-between gap-4">
        <div class="sticky w-1/4">
            <div class="flex flex-col justify-between p-2 space-y-2 bg-white min-h-max rounded-xl shadow-mine">
                <div class="flex items-center justify-center w-full gap-2 text-center">
                    <div class="text-xl">{{ $job->no_job }}</div>
                    @if ($job->status)
                        <div class="px-2 py-1 text-xs text-white rounded-lg bg-mine-200">Done</div>
                    @else
                        <div class="px-2 py-1 text-xs text-white rounded-lg bg-mine-400">Progres</div>
                    @endif
                </div>
                @foreach ($job->job_detail as $detail)
                    @php
                        $total += $detail->amount;
                    @endphp
                @endforeach
                <div class="">Shipment : {{ $job->shipment->name }}</div>
                <div class="">Marketing : {{ $job->shipment->marketing->name }}</div>
                <div class="">Unique Goods : {{ $job->job_detail->count() }}</div>
                <div class="">Total Goods : {{ $total }}</div>
                <div class="">Destination : {{ $job->destination }}</div>
                <div class="">Shipping Date : {{ $job->shipping_date->format('Y-m-d') }}</div>
            </div>
        </div>
        @if (!$job->status)
            <x-primary-a href="{{ route('jobs.details.create', ['job' => $job->slug]) }}" class="h-fit">Add New
                Goods</x-primary-a>
        @endif
    </div>
    <div class="w-full p-2 mt-4 shadow-mine rounded-xl">
        <div class="flex w-full gap-4 h-7 ">
            <div class="text-center w-9">#</div>
            <div class="w-48">Added Date</div>
            <div class="w-48">goods code</div>
            <div class="w-32">amount</div>
            <div class="w-24">unit</div>
            <div class="w-24">In Stock</div>
            <div class="w-40 text-center">action</div>
        </div>
        @foreach ($job->job_detail as $item)
            <div class="flex w-full gap-4 h-9 ">
                <div class="text-center w-9">{{ $loop->iteration }}</div>
                <div class="w-48">{{ $item->created_at->format('d-m-Y') }}</div>
                <a href="{{ route('goods.show', ['good' => $item->goods->slug]) }}"
                    class="w-48">{{ $item->goods->code }}</a>
                <div class="w-32">{{ $item->amount }}</div>
                <div class="w-24 ">{{ $item->goods->unit }}</div>
                <div class="w-24 ">{{ $item->goods->stock }}</div>
                <div class="w-40">
                    @if (!$job->status)
                        <div class="flex justify-center gap-4">
                            <x-action-set :edit="route('jobs.details.edit', [
                                'job' => $job->slug,
                                'detail' => $item->id,
                            ])" :delete="route('jobs.details.destroy', [
                                'job' => $job->slug,
                                'detail' => $item->id,
                            ])"></x-action-set>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
