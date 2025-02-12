<x-app-layout title="All Suppliers" back="{{ route('shipments.index') }}">
    <div class="flex justify-between">
        <div class="w-1/2">
            <x-searchbar></x-searchbar>
        </div>
        <x-primary-a href="{{ route('suppliers.create', ['shipment' => $shipment->slug]) }}">Add New
            Suppliers</x-primary-a>
    </div>
    <div class="grid grid-cols-4 gap-4 mt-4">
        @foreach ($suppliers as $item)
            <div class="flex w-full gap-4 shadow-lg rounded-xl min-h-60">

                <div class="flex flex-col justify-between gap-2 p-4">
                    <div class="w-full text-xl text-center ">{{ $item->name }}</div>
                    <div class="">
                        <div class="capitalize ">phone : <a target="_blank" class="underline underline-offset-1"
                                href="https://api.whatsapp.com/send/?phone={{ $item->phone }}&text&type=phone_number&app_absent=0">{{ $item->phone }}</a>
                        </div>
                        <div class="">Shipment : {{ $item->shipment->name }}</div>
                        <div class="">Address : {{ $item->address }}</div>
                        <div class="">Total Goods : {{ $item->goods->count() }}</div>
                        <div class="">Total Goods in Stock : {{ $item->goods->sum('stock') }}</div>
                        <div class="">Total Goods at Supplier : {{ $item->goods->sum('at_supplier') }}</div>
                    </div>
                    <div class="flex justify-center gap-4">
                        <x-action-set :showLabel="'Goods'" :show="route('goods.index', ['supplier' => $item->slug, 'shipment' => $shipment->slug])" :edit="route('suppliers.edit', ['supplier' => $item->slug, 'shipment' => $shipment->slug])"></x-action-set>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
