@props([
    'title' => 'Peringatan',
    'type' => 'info',
])

<div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
    <div class="bg-white w-96 rounded-lg shadow-lg p-6">

        <h3 class="text-lg font-bold mb-3
            @if($type === 'error') text-red-600
            @elseif($type === 'warning') text-yellow-600
            @else text-blue-600
            @endif
        ">
            {{ $title }}
        </h3>

        <div class="text-gray-700 text-sm whitespace-pre-line">
            {{ $slot }}
        </div>

        <div class="mt-5 text-right">
            <button
                onclick="this.closest('.fixed').remove()"
                class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700"
            >
                Mengerti
            </button>
        </div>

    </div>
</div>
