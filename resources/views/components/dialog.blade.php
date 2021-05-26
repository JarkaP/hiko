<div class="overflow-auto" style="display:none;background-color: rgba(0,0,0,0.5)" x-show="{{ $slug }}"
    :class="{ 'absolute inset-0 z-10 flex items-start justify-center': {{ $slug }} }">
    <div class="m-4 bg-gray-100 rounded-md shadow-2xl sm:m-8 w-96" x-show="{{ $slug }}" x-on:click.away="{{ $slug }} = false">
        <div class="flex items-center justify-between px-4 py-2 text-xl border-b">
            <h6 class="text-xl font-bold">{{ $title }}</h6>
            <button type="button" x-on:click="{{ $slug }} = false" aria-label="{{ __('Zavřít')}}">
                ✖
            </button>
        </div>
        <div class="px-4 py-2">
            {{ $slot }}
        </div>
    </div>
</div>

