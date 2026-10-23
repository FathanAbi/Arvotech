@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto px-6 py-12">
    @if(session('success'))

    <div
        class="mb-6 p-4 rounded-xl
            bg-green-100 text-green-700">

        {{ session('success') }}

    </div>

    @endif
    <div
        class="bg-white dark:bg-gray-800
               border border-gray-200 dark:border-gray-700
               rounded-2xl shadow p-8">

        <h1
            class="text-3xl font-bold mb-6
                   text-gray-900 dark:text-white">

            Detail Pesanan

        </h1>

        <div class="space-y-4">

            <div>
                <strong>No Invoice:</strong>
                {{ $order->invoice_number }}
            </div>

            <div>
                <strong>Nama:</strong>
                {{ $order->customer_name }}
            </div>

            <div>
                <strong>Email:</strong>
                {{ $order->email }}
            </div>

            <div>
                <strong>Telepon:</strong>
                {{ $order->phone }}
            </div>

            <div>
                <strong>Paket:</strong>
                {{ $order->package->nama_paket }}
            </div>

            <div>
                <strong>Jumlah:</strong>
                {{ $order->quantity }}
            </div>

            <div>
                <strong>Total:</strong>
                Rp {{ number_format($order->total_price) }}
            </div>

            <div>
                <strong>Status:</strong>

                <span
                    class="bg-yellow-100
                           text-yellow-800
                           px-3 py-1 rounded-full">

                    {{ $order->status }}

                </span>

            </div>

        </div>

        <div
            class="mt-8 p-5 rounded-xl
                   bg-orange-50 dark:bg-orange-900/20">

            <h3
                class="font-bold text-orange-600">

                Informasi Pembayaran

            </h3>

            <p class="mt-2">

                Silakan transfer ke rekening berikut:

            </p>

            <div class="mt-4">

                <p>
                    Bank BCA
                </p>

                <p>
                    1234567890
                </p>

                <p>
                    a.n. Lontong Balap Mas Rusdi
                </p>

            </div>

            @if(
                $order->status === 'pending_payment' ||
                $order->status === 'rejected'
            )

            <div
                class="mt-8 p-6 rounded-xl
                    border border-gray-200 dark:border-gray-700">

                <h3
                    class="font-bold text-lg
                        text-gray-900 dark:text-white">

                    Upload Bukti Pembayaran

                </h3>

                <p
                    class="mt-2 text-sm
                        text-gray-600 dark:text-gray-400">

                    Upload foto bukti transfer setelah melakukan pembayaran.

                </p>

                <form
                    action="{{ $uploadUrl }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="mt-4">

                    @csrf

                    <input
                        id="payment_proof"
                        type="file"
                        name="payment_proof"
                        accept="image/*"
                        class="w-full px-4 py-3 rounded-xl
                            border border-gray-300 dark:border-gray-600">

                    @error('payment_proof')

                        <p class="mt-2 text-red-500 text-sm">
                            {{ $message }}
                        </p>

                    @enderror

                    <img
                        id="preview"
                        class="hidden mt-4 max-w-sm rounded-xl border">

                    <button
                        type="submit"
                        class="mt-4 bg-orange-500 hover:bg-orange-600
                            text-white px-6 py-3 rounded-xl">

                        Upload Bukti Transfer

                    </button>

                </form>

            </div>

            @endif

            <p class="mt-4 text-sm">

                Setelah pembayaran diverifikasi admin,
                status pesanan akan diperbarui.

            </p>

        </div>
        

        @if($order->payment_proof)

        <div
            class="mt-8 p-5 rounded-xl
                bg-green-50 dark:bg-green-900/20">

            <h3
                class="font-bold text-green-600 mb-4">

                Bukti Pembayaran

            </h3>

            <img
                src="{{ $proofUrl }}"
                alt="Bukti Pembayaran"
                class="max-w-md rounded-xl border">

        </div>

        @endif

    </div>

</div>

<script>

const input =
    document.getElementById(
        'payment_proof'
    );

if(input){

    input.addEventListener(
        'change',
        function(e){

            const file =
                e.target.files[0];

            if(!file) return;

            const reader =
                new FileReader();

            reader.onload =
                function(event){

                    const preview =
                        document.getElementById(
                            'preview'
                        );

                    preview.src =
                        event.target.result;

                    preview.classList.remove(
                        'hidden'
                    );
                };

            reader.readAsDataURL(
                file
            );
        }
    );
}

</script>
@endsection