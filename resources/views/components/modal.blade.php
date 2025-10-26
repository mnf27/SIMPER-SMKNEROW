@props([
    'title' => '',
    'show' => 'openModal',
])

<div 
    x-show="{{ $show }}"
    x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
    style="display: none;"
    x-cloak
>
    <div 
        x-show="{{ $show }}"
        x-transition.scale
        class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6 relative"
    >
        {{-- Header --}}
        <h3 class="text-lg font-semibold mb-4">{{ $title }}</h3>

        {{-- Isi modal --}}
        {{ $slot }}

        {{-- Tombol close --}}
        <button 
            @click="{{ $show }} = false"
            class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-lg">
            ✕
        </button>
    </div>
</div>
