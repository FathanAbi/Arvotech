@extends('layouts.admin')

@section('content')

<div class="flex justify-between mb-6">

    <h1 class="text-3xl font-bold">
        Paket Catering
    </h1>

    <a
        href="{{ route('admin.catering-packages.create') }}"
        class="bg-orange-500 text-white px-4 py-2 rounded">

        Tambah Paket

    </a>

</div>

@if(session('error'))
    <div class="mb-4 bg-red-100 text-red-700 px-4 py-3 rounded">
        {{ session('error') }}
    </div>
@endif

@if(session('success'))
    <div class="mb-4 bg-green-100 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white dark:bg-gray-800 rounded-xl shadow">

<table class="w-full">

<thead>

<tr>

<th class="p-4 text-left">
Nama Paket
</th>

<th class="p-4 text-left">
Harga
</th>

<th class="p-4 text-left">
Aksi
</th>

</tr>

</thead>

<tbody>

@foreach($packages as $package)

<tr class="border-t dark:border-gray-700">

<td class="p-4">
{{ $package->nama_paket }}
</td>

<td class="p-4">
Rp {{ number_format($package->harga) }}
</td>

<td class="p-4">

<div class="flex gap-2">

<a
href="{{ route('admin.catering-packages.edit',$package) }}"
class="bg-yellow-500 text-white px-3 py-1 rounded">

Edit

</a>

<form
action="{{ route('admin.catering-packages.destroy',$package) }}"
method="POST">

@csrf
@method('DELETE')

<button
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