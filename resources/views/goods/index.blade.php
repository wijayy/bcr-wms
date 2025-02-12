<x-app-layout title="{{ $title }}" back="{{ route('suppliers.index', ['shipment' => $shipment->slug]) }}">
    <div class="flex justify-between">
        <div class="w-1/2">
            <x-searchbar></x-searchbar>
        </div>
        <x-primary-a
            href="{{ route('goods.create', ['shipment' => $shipment->slug, 'supplier' => $supplier->slug]) }}">Add New
            Goods</x-primary-a>
    </div>
    <div class="grid grid-cols-4 gap-4 mt-4">
        @foreach ($goods as $item)
            {{-- @dd($item) --}}
            <div class="w-full shadow-mine rounded-xl">
                <div class="bg-center bg-no-repeat bg-cover aspect-video rounded-t-xl"
                    style="background-image: url({{ asset("storage/$item->image") }})"></div>
                <div class="w-full gap-2 p-2">
                    <div class="w-full text-xl text-center">{{ $item->code }}</div>
                    <div class="">
                        {{-- <div class="">Shipment : {{ $item->shipment->name }}</div> --}}
                        <div class="">Shipment : {{ $item->supplier->shipment->name }}</div>
                        <div class="">Supplier : {{ $item->supplier->name }}</div>
                        <div class="">Material : {{ $item->material ?? '-' }} </div>
                        <div class="">Net Weight : {{ $item->weight ?? '-' }} Kg</div>
                        <div class="">In Stock : {{ $item->stock }} {{ $item->unit }}</div>
                        <div class="">Goods at Supplier : {{ $item->at_supplier }} {{ $item->unit }}</div>
                        <div class="">Price : ${{ $format_number($item->us_price, 2) }} | IDR
                            {{ $format_number($item->id_price) }}</div>
                        <div class="">Total Price : ${{ $format_number($item->us_price * $item->stock, 2) }} |
                            IDR {{ $format_number($item->id_price * $item->stock) }}</div>
                    </div>
                    <div class="flex justify-center w-full gap-2">
                        <x-action-set :show="route('goods.show', [
                            'shipment' => $shipment->slug,
                            'supplier' => $supplier->slug,
                            'good' => $item->slug,
                        ])" :edit="route('goods.edit', [
                            'shipment' => $shipment->slug,
                            'supplier' => $supplier->slug,
                            'good' => $item->slug,
                        ])" :delete="route('goods.destroy', [
                            'shipment' => $shipment->slug,
                            'supplier' => $supplier->slug,
                            'good' => $item->slug,
                        ])" :plus="route('stocks.index', [
                            'shipment' => $shipment->slug,
                            'supplier' => $supplier->slug,
                            'good' => $item->slug,
                        ])"
                            :plusLabel="'Add Stocks'"></x-action-set>
                    </div>
                </div>
            </div>
        @endforeach
    </div>



</x-app-layout>
