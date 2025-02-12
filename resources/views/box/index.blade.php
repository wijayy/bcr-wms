<x-app-layout title="All Boxes in Job {{ $job->no_job }}"
    back="{{ route('jobs.index', ['shipment' => $shipment->slug]) }}">
    <div class="flex justify-end gap-4">
        <x-primary-a href="{{ route('export.box', ['job' => $job->slug]) }}" class="">Export</x-primary-a>
        @if (!$job->status)
            <x-primary-a href="{{ route('boxes.create', ['shipment' => $shipment->slug, 'job' => $job->slug]) }}">Add
                New Box</x-primary-a>
        @endif
    </div>
    <div class="p-4 mt-4 space-y-3 overflow-x-auto rounded-lg lg:overflow-x-hidden shadow-mine">
        <div class="flex items-center gap-4">
            <div class="flex items-center justify-center w-24">No Box</div>
            <div class="flex items-center justify-center w-24 text-center">Total Box</div>
            <div class="flex items-center justify-center w-40 text-center">Total Goods</div>
            <div class="flex items-center justify-center w-40 text-center">Volume (CBM)</div>
            <div class="flex items-center justify-center w-40 text-center">Tare Weight (KgM)</div>
            <div class="flex items-center justify-center w-40 text-center">Gross Weight (KgM) </div>
            <div class="flex items-center justify-center text-center w-60">Action</div>
            <div class="flex items-center justify-between w-full">
                <div class="w-full">Goods</div>
                <div class="w-32 text-center">Amount</div>
                <div class="w-32 text-center">In Stock</div>
                <div class="w-32 text-center">At Supplier</div>
                <div class="w-32 text-center">Action</div>
            </div>
        </div>
        @foreach ($boxes as $item)
            @php
                $gw = $item->weight;
                foreach ($item->box_detail as $value) {
                    $gw += $value->amount < 0 ? $value->stocks->weight : $value->stocks->goods->weight * $value->amount;
                }
            @endphp
            <div class="flex gap-4 even:bg-mine-100 even:rounded-lg">
                <div class="flex items-center justify-center w-24">{{ $item->no_box }}{{ $item->prefix }}</div>
                <div class="flex items-center justify-center w-24">{{ $item->count }}</div>
                <div class="flex items-center justify-center w-40">
                    {{ $item->box_detail->where('amount', '>', 0)->sum('amount') }}</div>
                <div class="flex items-center justify-center w-40 text-center">
                    {{ ($item->length * $item->width * $item->height) / ($item->count * 1000000) }}</div>
                <div class="flex items-center justify-center w-40 text-center">
                    {{ $item->weight }}</div>
                <div class="flex items-center justify-center w-40 text-center">
                    {{ $gw }}</div>
                <div class="flex flex-wrap items-center justify-center gap-2 w-60 bg-red-500text-center">
                    @if (!$job->status)
                        <x-action-set :edit="route('boxes.edit', [
                            'shipment' => $shipment->slug,
                            'job' => $job->slug,
                            'box' => $item->slug,
                        ])" :delete="route('boxes.destroy', [
                            'shipment' => $shipment->slug,
                            'job' => $job->slug,
                            'box' => $item->slug,
                        ])" :plus="route('detail.create', [
                            'shipment' => $shipment->slug,
                            'job' => $job->slug,
                            'box' => $item->slug,
                        ])"
                            :plusLabel="'Add Goods'"></x-action-set>
                    @endif
                </div>
                <div class="w-full py-2">
                    @foreach ($item->box_detail as $detail)
                        @php
                            $edit =
                                $detail->amount < 0
                                    ? null
                                    : route('detail.edit', [
                                        'shipment' => $shipment->slug,
                                        'job' => $job->slug,
                                        'box' => $item->slug,
                                        'detail' => $detail->id,
                                    ]);
                        @endphp
                        <div class="flex w-full py-1 border-b last:border-none border-mine-200">
                            <div class="flex w-full gap-2">
                                <a class="underline underline-offset-2"
                                    href="{{ route('goods.index', ['shipment' => $shipment, 'supplier' => $detail->stocks->goods->supplier->slug]) }}">{{ $detail->stocks->goods->supplier->name }}</a>
                                <a class="underline underline-offset-2"
                                    href="{{ route('goods.show', ['shipment' => $shipment, 'supplier' => $detail->stocks->goods->supplier->slug, 'good' => $detail->stocks->goods->slug]) }}">{{ $detail->stocks->goods->code }}</a>
                                <p>{{ $detail->stocks->name }}</p>
                            </div>
                            <div class="w-32 text-center">{{ $detail->amount >= 0 ? $detail->amount : 'typeof' }}</div>
                            <div class="w-32 text-center">{{ $detail->stocks->goods->stock }}</div>
                            <div class="w-32 text-center">{{ $detail->stocks->goods->at_supplier }}</div>
                            <div class="flex flex-wrap justify-center w-32 gap-2 text-center">
                                @if (!$job->status)
                                    <x-action-set :editLabel="'edit amount'" :deleteLabel="'remove goods'" :edit="$edit"
                                        :delete="route('detail.destroy', [
                                            'shipment' => $shipment->slug,
                                            'job' => $job->slug,
                                            'box' => $item->slug,
                                            'detail' => $detail->id,
                                        ])"></x-action-set>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        @endforeach
    </div>
</x-app-layout>
