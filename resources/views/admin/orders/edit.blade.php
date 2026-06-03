@extends('layouts.app')

@section('content')

<div class="container">

<h2>Edit Status</h2>

<form
method="POST"
action="{{ route('admin.orders.update',$order) }}">

@csrf
@method('PUT')

<select
name="status"
class="form-select">

<option value="pending_payment">
Menunggu Pembayaran
</option>

<option value="waiting_verification">
Menunggu Verifikasi
</option>

<option value="processing">
Diproses
</option>

<option value="shipping">
Dikirim
</option>

<option value="completed">
Selesai
</option>

<option value="rejected">
Ditolak
</option>

</select>

<button
class="btn btn-primary mt-3">

Simpan

</button>

</form>

</div>

@endsection