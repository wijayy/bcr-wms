@php
    $title = $detail ?? false ? "Edit Box $boxes->no_box $boxes->prefix Data" : "Add New box for job $job->no_job";
    if ($detail ?? false) {
        $value = [
            'goods_id' => $detail->stocks->goods_id,
            'amount' => 0,
            'name' => $detail->stocks->name,
            'type' => 'depart',
        ];
    }
@endphp

{{-- @dd(json_encode($detail)) --}}

<x-app-layout title="{{ $title }}"
    back="{{ route('boxes.index', ['shipment' => $shipment->slug, 'job' => $sjob->slug]) }}">
    <form
        action="{{ $detail
            ? route('detail.update', [
                'shipment' => $shipment->slug,
                'job' => $job->slug,
                'box' => $boxes->slug,
                'detail' => $detail->id,
            ])
            : route('detail.store', ['shipment' => $shipment->slug, 'job' => $job->slug, 'box' => $boxes->slug]) }}"
        method="post">
        @csrf
        @if ($detail ?? false)
            @method('put')
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- @dd($detail) --}}

        <div class="grid grid-cols-1 gap-4 mt-4"
            x-data='{
            items: @json($detail->only(['amount'])),

            addItem() {
                this.items.push({
                    goods_id: "",
                    amount: "",
                    name: "",
                });
            },
            removeItem(index) {
                this.items.splice(index, 1);
            }
        }'>
            {{-- <pre>{{ json_encode($boxdetail, JSON_PRETTY_PRINT) }}</pre> --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (!$detail ?? false)
                <div class="flex justify-center w-full mt-5">
                    <x-action-a @click.prevent="addItem()" class="w-1/2 h-8 lg:w-1/5 bg-mine-200 hover:scale-100">
                        <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path d="M6 12H18M12 6V18" stroke="#000000" stroke-width="1" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </g>
                        </svg>
                    </x-action-a>
                </div>
            @endif

            <div class="space-y-4">

                <template x-for="(item, index) in items" :key="index">
                    <div class="flex gap-4" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                        <div
                            class="grid w-full {{ !$detail ?? false ? 'grid-cols-4' : 'grid-cols-3' }} gap-6 p-2 rounded-md shadow-mine">
                            <!-- Goods ID -->
                            <div class="">
                                <x-input-label for="goods_id" :value="__('Goods')" />
                                <x-select-input id="goods_id" class="lg:rounded-l-none" x-model="item.goods_id"
                                    ::name="'box[' + index + '][goods_id]'" class="">
                                    @foreach ($goods as $item)
                                        <x-select-option :selected="$item->id == old('goods_id')"
                                            value="{{ $item->id }}">{{ $item->supplier->name }}
                                            {{ $item->code }}</x-select-option>
                                    @endforeach
                                </x-select-input>
                                <!-- Menampilkan Error -->
                                <template x-if="$store.errors.has('box[' + index + '][goods_id]')">
                                    <p class="text-sm text-red-500"
                                        x-text="$store.errors.get('box[' + index + '][goods_id]')"></p>
                                </template>
                            </div>

                            <!-- Amount -->
                            <div class="">
                                <x-input-label for="amount" :value="__('Amount (-1 typeof)')" />
                                <x-text-input x-model="item.amount" id="amount" min="1"
                                    class="block w-full mt-1" type="number" min="1" ::name="'box[' + index + '][amount]'" required
                                    autocomplete="amount" />
                                <!-- Menampilkan Error -->
                                <template x-if="$store.errors.has('box[' + index + '][amount]')">
                                    <p class="text-sm text-red-500"
                                        x-text="$store.errors.get('box[' + index + '][amount]')"></p>
                                </template>
                            </div>

                            <div class="">
                                <x-input-label for="type" :value="__('type')" />
                                <x-select-input id="type" class="lg:rounded-l-none" x-model="item.type"
                                    ::name="'box[' + index + '][type]'" class="">
                                    <x-select-option value="depart">Depart</x-select-option>
                                    <x-select-option value="stock">stock</x-select-option>

                                </x-select-input>
                                <!-- Menampilkan Error -->
                                <template x-if="$store.errors.has('box[' + index + '][type]')">
                                    <p class="text-sm text-red-500"
                                        x-text="$store.errors.get('box[' + index + '][type]')"></p>
                                </template>
                            </div>

                        </div>

                        @if (!$detail ?? false)
                            <div class="flex items-center">
                                <x-action-a class="cursor-pointer bg-rose-500 size-8"
                                    @click.prevent="removeItem(index)">
                                    <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M4 6H20M16 6L15.7294 5.18807C15.4671 4.40125 15.3359 4.00784 15.0927 3.71698C14.8779 3.46013 14.6021 3.26132 14.2905 3.13878C13.9376 3 13.523 3 12.6936 3H11.3064C10.477 3 10.0624 3 9.70951 3.13878C9.39792 3.26132 9.12208 3.46013 8.90729 3.71698C8.66405 4.00784 8.53292 4.40125 8.27064 5.18807L8 6M18 6V16.2C18 17.8802 18 18.7202 17.673 19.362C17.3854 19.9265 16.9265 20.3854 16.362 20.673C15.7202 21 14.8802 21 13.2 21H10.8C9.11984 21 8.27976 21 7.63803 20.673C7.07354 20.3854 6.6146 19.9265 6.32698 19.362C6 18.7202 6 17.8802 6 16.2V6M14 10V17M10 10V17"
                                            stroke="#000000" stroke-width="1" stroke-linecap="round"
                                            stroke-linejoin="round">
                                        </path>
                                    </svg>
                                </x-action-a>
                            </div>
                        @endif

                    </div>
                </template>

            </div>
        </div>


        <div class="flex justify-center w-full mt-4">
            <x-primary-a onclick="event.preventDefault();
        this.closest('form').submit();">Submit</x-primary-a>
        </div>
    </form>
</x-app-layout>
