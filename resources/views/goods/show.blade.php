<x-app-layout
    title="Goods {{ $goods->code }}`s Shipment {{ $shipment->name }} from Supplier {{ $supplier->name }} Stock Record"
    back="{{ route('goods.index', ['shipment' => $shipment->slug, 'supplier' => $supplier->slug]) }}">
    <div class="flex justify-end">
        <x-primary-a
            href="{{ route('stocks.index', ['good' => $goods->slug, 'shipment' => $shipment->slug, 'supplier' => $supplier->slug]) }}">Add
            New Record</x-primary-a>
    </div>

    <div class="w-full ">
        <div class="flex w-1/2 rounded-xl shadow-mine">
            <div class="w-1/2 bg-center bg-no-repeat bg-cover aspect-video rounded-xl"
                style="background-image: url({{ asset("storage/$goods->image") }})"></div>
            <div class="w-1/2 p-2 space-y-2">
                <div class="text-xl text-center">Code : {{ $goods->code }}</div>
                <div class="grid w-full grid-cols-2 gap-2">
                    <div class="">Net Weight : {{ $goods->weight ?? '-' }} Kg
                    </div>
                    <div class="">in Stock : {{ $goods->stock > 0 ? $goods->stock : 'typeof' }}</div>
                    <div class="">At Supplier : {{ $goods->at_supplier }}</div>
                </div>
                <div class="">Shipment : {{ $goods->supplier->shipment->name }}
                </div>
                <div class="">Supplier : {{ $goods->supplier->name }}
                </div>
                <div class="">Price : ${{ $format_number($goods->us_price, 2) }} | IDR
                    {{ $format_number($goods->id_price) }}</div>
                <div class="">Total Price : ${{ $format_number($goods->us_price * $goods->stock, 2) }} |
                    IDR {{ $format_number($goods->id_price * $goods->stock) }}</div>
            </div>
        </div>
    </div>

    <div class="w-full p-2 mt-4 space-y-4 overflow-auto rounded-xl shadow-mine" x-data="{
        image: '',
    }">
        <div class="relative w-1/4 " x-show="image">
            <img :src="image" alt="">
            <div class="absolute top-0 right-0 flex items-center justify-center transition rounded-lg cursor-pointer backdrop-blur-md size-7 hover:scale-110 bg-mine-100/40"
                @click="image = ''">
                <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path d="M6 6L18 18M18 6L6 18" stroke="#000000" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </g>
                </svg>
            </div>
        </div>
        <div class="block w-full">
            <div class="flex gap-4 ">
                <div class="text-start w-7">#</div>
                <div class="w-64 text-start">Date</div>
                <div class="w-28 text-start">Type</div>
                <div class="w-32 text-start">Amount</div>
                <div class="w-24 text-start">Stocks</div>
                <div class="w-60 text-start">Name of Part</div>
                <div class="w-60 text-start">Weight of Part</div>
                <div class="w-full text-start">Desc</div>
                <div class="w-36 text-start">Note</div>
            </div>
            @foreach ($goods->stocks as $item)
                <div class="flex gap-4 py-2">
                    <div class="text-start w-7">{{ $loop->iteration }}</div>
                    <div class="w-64 text-start">{{ $item->created_at->format('d-m-Y: H:m:s') }}</div>
                    <div class="w-32 text-start">{{ $item->type }}</div>
                    <div class="w-28 text-start">{{ $item->amount > 0 ? $item->amount : 'typeof' }}</div>
                    <div class="w-24 text-start">{{ $item->stock }}</div>
                    <div class="w-60 text-start">{{ $item->name }}</div>
                    <div class="w-60 text-start">{{ $item->weight }}</div>
                    <div class="w-full text-start">{{ $item->desc }}</div>
                    <div class="flex gap-2 w-36 text-start">
                        @if ($item->note ?? false)
                            <div class="flex items-center justify-center transition rounded-lg cursor-pointer size-7 bg-mine-200 hover:scale-110"
                                @click="image = '{{ asset("storage/$item->note") }}'">
                                <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                    </g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M3 14C3 9.02944 7.02944 5 12 5C16.9706 5 21 9.02944 21 14M17 14C17 16.7614 14.7614 19 12 19C9.23858 19 7 16.7614 7 14C7 11.2386 9.23858 9 12 9C14.7614 9 17 11.2386 17 14Z"
                                            stroke="#fff" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                        </path>
                                    </g>
                                </svg>
                            </div>
                            <form action="{{ route('download', ['stock' => $item->id]) }}" method="post">
                                @csrf
                                <button type="submit"
                                    class="flex items-center justify-center transition rounded-lg cursor-pointer size-7 hover:scale-110 bg-mine-300">
                                    <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                        </g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path
                                                d="M8 11L12 15M12 15L16 11M12 15V3M21 11V17.7992C21 18.9193 21 19.4794 20.782 19.9072C20.5903 20.2835 20.2843 20.5895 19.908 20.7812C19.4802 20.9992 18.9201 20.9992 17.8 20.9992H6.2C5.0799 20.9992 4.51984 20.9992 4.09202 20.7812C3.71569 20.5895 3.40973 20.2835 3.21799 19.9072C3 19.4794 3 18.9193 3 17.7992V11"
                                                stroke="#fff" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                        </g>
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
