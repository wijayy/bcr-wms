<x-app-layout title="All {{ $marketing->name ?? '' }} Shipment">
    <div class="flex justify-between">
        <div class="w-1/2">
            <x-searchbar></x-searchbar>
        </div>
        <x-primary-a href="{{ route('shipments.create') }}">Add New Shipment</x-primary-a>
    </div>

    <div class="grid grid-cols-2 gap-4 mt-4 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
        @foreach ($shipments as $item)
            <div class="flex flex-col justify-between p-4 rounded-xl aspect-square shadow-mine">
                <div class="space-y-2">
                    <div class="flex items-center justify-center text-2xl text-center ">{{ $item->name }}</div>
                    <div class="">Marketing :
                        {{ $item->marketing ? $item->marketing->name : 'No Marketing Assigned' }}</div>
                    <div class="">Jobs Done : {{ $item->jobs->where('status', 1)->count() }}</div>
                    <div class="">Jobs Progress : {{ $item->jobs->where('status', 0)->count() }}</div>
                    <div class="">Departed Goods :
                        {{ $item->jobs->where('status', 0)->first()?->box_detail->where('amount', '>', 0)->sum('amount') }}
                    </div>
                    <div class="">goods in Stock : {{ $item->goods->sum('stock') }}</div>
                    <div class="">goods at Supplier : {{ $item->goods->sum('at_supplier') }}</div>
                </div>
                <div class="bottom-0 flex justify-center w-full gap-4">
                    <x-action-a href="{{ route('jobs.index', ['shipment' => $item->slug]) }}" class="bg-mine-200 ">
                        <x-action-label>Job</x-action-label>
                        <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M13 3H8.2C7.0799 3 6.51984 3 6.09202 3.21799C5.71569 3.40973 5.40973 3.71569 5.21799 4.09202C5 4.51984 5 5.0799 5 6.2V17.8C5 18.9201 5 19.4802 5.21799 19.908C5.40973 20.2843 5.71569 20.5903 6.09202 20.782C6.51984 21 7.0799 21 8.2 21H10M13 3L19 9M13 3V7.4C13 7.96005 13 8.24008 13.109 8.45399C13.2049 8.64215 13.3578 8.79513 13.546 8.89101C13.7599 9 14.0399 9 14.6 9H19M19 9V10M9 17H11.5M9 13H14M9 9H10M14 21L16.025 20.595C16.2015 20.5597 16.2898 20.542 16.3721 20.5097C16.4452 20.4811 16.5147 20.4439 16.579 20.399C16.6516 20.3484 16.7152 20.2848 16.8426 20.1574L21 16C21.5523 15.4477 21.5523 14.5523 21 14C20.4477 13.4477 19.5523 13.4477 19 14L14.8426 18.1574C14.7152 18.2848 14.6516 18.3484 14.601 18.421C14.5561 18.4853 14.5189 18.5548 14.4903 18.6279C14.458 18.7102 14.4403 18.7985 14.405 18.975L14 21Z"
                                    stroke="#000000" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                            </g>
                        </svg>
                    </x-action-a>
                    <x-action-a href="{{ route('suppliers.index', ['shipment' => $item->slug]) }}"
                        class="bg-mine-200 ">
                        <x-action-label>Supplier</x-action-label>

                        <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">

                            <g id="SVGRepo_bgCarrier" stroke-width="0" />

                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" />

                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M4 14H6.67452C7.1637 14 7.40829 14 7.63846 14.0553C7.84254 14.1043 8.03763 14.1851 8.21657 14.2947C8.4184 14.4184 8.59136 14.5914 8.93726 14.9373L9.06274 15.0627C9.40865 15.4086 9.5816 15.5816 9.78343 15.7053C9.96237 15.8149 10.1575 15.8957 10.3615 15.9447C10.5917 16 10.8363 16 11.3255 16H12.6745C13.1637 16 13.4083 16 13.6385 15.9447C13.8425 15.8957 14.0376 15.8149 14.2166 15.7053C14.4184 15.5816 14.5914 15.4086 14.9373 15.0627L15.0627 14.9373C15.4086 14.5914 15.5816 14.4184 15.7834 14.2947C15.9624 14.1851 16.1575 14.1043 16.3615 14.0553C16.5917 14 16.8363 14 17.3255 14H20M7.2 4H16.8C17.9201 4 18.4802 4 18.908 4.21799C19.2843 4.40973 19.5903 4.71569 19.782 5.09202C20 5.51984 20 6.07989 20 7.2V16.8C20 17.9201 20 18.4802 19.782 18.908C19.5903 19.2843 19.2843 19.5903 18.908 19.782C18.4802 20 17.9201 20 16.8 20H7.2C6.0799 20 5.51984 20 5.09202 19.782C4.71569 19.5903 4.40973 19.2843 4.21799 18.908C4 18.4802 4 17.9201 4 16.8V7.2C4 6.0799 4 5.51984 4.21799 5.09202C4.40973 4.71569 4.71569 4.40973 5.09202 4.21799C5.51984 4 6.0799 4 7.2 4Z"
                                    stroke="#000000" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" />
                            </g>
                        </svg>
                    </x-action-a>
                    <x-action-set :edit="route('shipments.edit', ['shipment' => $item->slug])" :delete="route('shipments.destroy', ['shipment' => $item->slug])"></x-action-set>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
