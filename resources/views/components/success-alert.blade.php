<div>
    @if (!empty($message))
        <div x-data="{ show: true }" x-show="show" style="display:inline-block"
            class="max-w-sm px-6 py-3 mb-4 text-white bg-green-700 rounded-md"
            x-init="() => { console.log('aaa'); window.scrollTo(0, 0); console.log('bbbb'); setTimeout(() => show = false, 2000) }">
            {{ $message }}
        </div>
    @endif
</div>
