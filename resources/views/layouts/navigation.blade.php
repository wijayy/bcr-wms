<div class="z-50 flex items-center justify-between min-w-full gap-4 p-4 bg-white shadow-md rounded-xl">
    <div class="flex gap-2">
        @if ($back ?? false)
            <a href="{{ $back }}"
                class="flex items-center justify-center p-1 transition rounded-lg hover:scale-105 bg-mine-200"><svg
                    width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path d="M4 12H20M4 12L8 8M4 12L8 16" stroke="#fff" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </g>
                </svg></a>
        @endif
        <div class="">
            <div class="text-md lg:text-2xl font-comfortaa">{{ $title }}</div>
            @if (session()->has('success'))
                <div class="text-sm capitalize">{{ session('success') }}</div>
            @endif
            @if (session()->has('error'))
                <div class="text-sm capitalize text-rose-500">{{ session('error') }}</div>
            @endif
        </div>
    </div>
    <div class="relative" x-data="{
        more: false
    }">
        <div x-on:click="more = !more" class="flex items-center gap-2 cursor-pointer">
            {{ Auth::user()->name ?? 'admin' }}
            <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                    <path d="M6 9L12 15L18 9" stroke="#000000" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round"></path>
                </g>
            </svg>
        </div>
        <div x-show="more" x-transition:enter="transition ease-out duration-300" @click.outside="more = false"
            x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="absolute px-4 py-2 space-y-2 translate-y-6 bg-white rounded-lg shadow-lg translate-x-1/3 top-full right-1/2">
            <a class="px-2 py-1 rounded-lg" href="{{ route('profile.edit') }}">Profile</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <a class="px-2 py-1 rounded-lg" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                                                this.closest('form').submit();">Logout</a>
            </form>
        </div>
    </div>
</div>
