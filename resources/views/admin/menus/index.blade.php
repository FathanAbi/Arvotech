@extends('layouts.admin')

@section('title', 'Kelola Menu')

@section('content')

<div class="flex justify-between mb-6">

    <h1 class="text-3xl font-bold">
        Kelola Menu
    </h1>

    <a
        href="{{ route('admin.menus.create') }}"
        class="bg-orange-500 text-white px-4 py-2 rounded-lg">

        Tambah Menu

    </a>

</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow">

    <table class="w-full">

        <thead>

        <tr class="border-b dark:border-gray-700">

            <th class="p-4 text-left">Nama</th>
            <th class="p-4 text-left">Harga</th>
            <th class="p-4 text-left">Aksi</th>

        </tr>

        </thead>

        <tbody>

        @foreach($menus as $menu)

            <tr class="border-b dark:border-gray-700">

                <td class="p-4">
                    {{ $menu->nama }}
                </td>

                <td class="p-4">
                    Rp {{ number_format($menu->harga) }}
                </td>

                <td class="p-4">

                    <div class="flex gap-2">

                        <a
                            href="{{ route('admin.menus.edit', $menu) }}"
                            class="bg-yellow-500 text-white px-3 py-1 rounded">

                            Edit

                        </a>

                        <form
                            action="{{ route('admin.menus.destroy', $menu) }}"
                            method="POST">

                            @csrf
                            @method('DELETE')

                            <button
                                onclick="return confirm('Hapus menu?')"
                                class="bg-red-500 text-white px-3 py-1 rounded">

                                Hapus

                            </button>

                        </form>

                    </div>

                </td>

            </tr>

        @endforeach

        </tbody>

    </table>

</div>

@endsection