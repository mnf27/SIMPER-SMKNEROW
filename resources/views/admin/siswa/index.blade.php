<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kelola Siswa
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data"
            class="mb-6 bg-white p-6 rounded shadow">
            @csrf
            <div class="flex items-center space-x-3">
                <input type="file" name="file" class="border rounded p-2 w-full">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Import</button>
            </div>
        </form>

        <div class="bg-white p-6 rounded shadow">
            <table class="w-full border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2">NIS</th>
                        <th class="border p-2">Nama</th>
                        <th class="border p-2">Kelas</th>
                        <th class="border p-2">Email</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswa as $s)
                        <tr>
                            <td class="border p-2">{{ $s->nis }}</td>
                            <td class="border p-2">{{ $s->nama }}</td>
                            <td class="border p-2">{{ $s->kelas }}</td>
                            <td class="border p-2">{{ $s->user->email }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $siswa->links() }}
            </div>
        </div>
    </div>
</x-app-layout>