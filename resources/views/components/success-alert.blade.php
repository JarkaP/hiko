@if (session()->has('success'))
    <div x-data="{ show: true }" x-show="show" style="display:inline-block"
        class="max-w-sm px-6 py-3 mb-4 text-white bg-green-700 rounded-md" x-init="setTimeout(() => show = false, 1500)">
        {{ session('success') }}
    </div>
@endif
