<div>
    @if (!empty($message))
        <div x-data="{ show: true }" x-show="show" style="display:inline-block"
            class="max-w-sm px-6 py-3 mb-4 text-white bg-green-700 rounded-md"
            x-init="() => { window.scrollTo(0, 0); setTimeout(() => {show = false; $wire.set('message', '')}, 2000); }"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90">
            {{ $message }}
        </div>
    @endif
</div>
