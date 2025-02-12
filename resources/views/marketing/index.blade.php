<x-app-layout title="All Marketing">
    <div class="flex justify-between">
        <div class="w-1/2">
            <x-searchbar></x-searchbar>
        </div>
        <x-primary-a href="{{ route('marketing.create') }}">Add New User</x-primary-a>
    </div>
    <div class="grid w-full grid-cols-1 gap-4 mt-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($marketing as $item)
            <div class="flex w-full shadow-lg rounded-xl">
                <div class="w-1/2 overflow-hidden bg-center bg-no-repeat bg-cover rounded-l-xl aspect-square"
                    style="background-image: url({{ asset("storage/$item->image") }})">
                </div>
                <div class="flex flex-col justify-between w-1/2 gap-4 p-3">
                    <div class="">
                        <div class="w-full text-lg text-center font-comfortaa">{{ $item->name }}</div>

                        <div class="flex flex-col text-xs sm:text-base gap-y-1 lg:gap-y-3 ">
                            <div class="">Email : {{ $item->email }}</div>
                            {{-- <div class="">Total Suppliers : {{ $item->suppliers->count() }}</div>
                            <div class="">Total Unique Goods : {{ $item->goods_count }}</div>
                            <div class="">Jobs Done : {{ $item->jobs_done }}</div>
                            <div class="">Total Goods : {{ $item->stock_count }}</div>
                            <div class="">Jobs Progres : {{ $item->jobs_progres }}</div> --}}
                        </div>
                    </div>
                    <div class="flex justify-center">
                        <x-action-set :show="route('shipments.index', ['marketing' => $item->slug])"></x-action-set>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
