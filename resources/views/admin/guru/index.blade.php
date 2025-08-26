<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kelola Guru
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
        @if(session('success'))
            <div class="bg-green-200 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form Upload Excel -->
        <form action="{{ route('guru.import') }}" method="POST" enctype="multipart/form-data" class="mb-6">
            @csrf
            <input type="file" name="file" required class="border p-2 rounded">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Import</button>
        </form>

        <!-- Tabel Guru -->
        <table class="w-full border-collapse border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2">NIP</th>
                    <th class="border p-2">Nama</th>
                    <th class="border p-2">Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach($guru as $g)
                    <tr>
                        <td class="border p-2">{{ $g->nip }}</td>
                        <td class="border p-2">{{ $g->nama }}</td>
                        <td class="border p-2">{{ $g->email }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>