@php
    $title = $marketing ? "Edit User $marketing->name Data" : 'Add New User';
@endphp

<x-app-layout title="{{ $title }}" back="{{ route('marketing.index') }}">
    <form enctype="multipart/form-data"
        action="{{ $marketing ? route('marketing.update', ['marketing' => $marketing->slug]) : route('marketing.store') }}"
        method="post">
        @csrf
        @if ($marketing ?? false)
            @method('put')
        @endif
        <div class="flex flex-wrap justify-start gap-4 lg:flex-no-wrap" x-data="">
            <div class="">
                <x-input-label for="image" :value="__('photo')" />
                <div class="relative flex mt-1 text-center rounded-md shadow-md size-40 aspect-square"
                    x-data="{
                        image: '{{ $marketing ? asset("storage/$marketing->image") : '' }}',
                        text: 'Photo',
                        imagePreview() {
                            return URL.createObjectURL(event.target.files[0]);
                        }
                    }">
                    <img :src="image" :alt="" class="z-10 object-cover rounded-md size-full"
                        x-show="image">
                    <input type="file" id="image" name="image" @change="image=imagePreview()" class="sr-only">
                    <label for="image" :class="{ 'opacity-100': !image, 'opacity-0': image }"
                        class="absolute top-0 left-0 z-20 flex items-center justify-center w-full h-full bg-transparent border border-black border-dashed rounded-md cursor-pointer ALIGN text-sky-500 hover:text-blue-700"
                        x-text="text"></label>
                </div>
                <x-input-error :messages="$errors->get('image')" class="mt-2" />
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 mt-4 lg:grid-cols-2">
            <div>
                <x-input-label for="name" :value="__('name')" />
                <x-text-input id="name" class="block w-full mt-1" type="text" name="name" :value="old('name', $marketing->name ?? '')"
                    required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="email" :value="__('email')" />
                <x-text-input id="email" class="block w-full mt-1" type="text" name="email" :value="old('email', $marketing->email ?? '')"
                    required autocomplete="email" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

        </div>
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            <div>
                <x-input-label for="password" :value="__('password')" />
                <x-text-input id="password" class="block w-full mt-1" type="password" name="password" :value="old('password', $marketing->password ?? '')"
                    required autocomplete="password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('confirm passowrd')" />
                <x-text-input id="password_confirmation" class="block w-full mt-1" type="password"
                    name="password_confirmation" :value="old('password_confirmation', $marketing->password_confirmation ?? '')" required autocomplete="password_confirmation" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
            <div class="">
                <x-input-label for="is_admin" :value="__('User Type')" />
                <div class="mt-2">
                    <x-radio-label class="">
                        <x-radio-input id="is_admin" name="is_admin" value="0" checked></x-radio-input>
                        Marketing
                    </x-radio-label>
                    <x-radio-label class="">
                        <x-radio-input id="is_admin" name="is_admin" value="1"></x-radio-input>
                        Admin
                    </x-radio-label>
                </div>
            </div>
        </div>
        <div class="flex justify-center mt-4">
            <x-primary-a onclick="event.preventDefault();
    this.closest('form').submit();">Submit</x-primary-a>
        </div>
    </form>
</x-app-layout>
