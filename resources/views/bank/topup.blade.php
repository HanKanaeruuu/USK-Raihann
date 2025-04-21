@extends('layouts.app')

@section('content')

    <div class="container">

        <h2>Top-Up Anda</h2>

        <p>Daftar top-up Anda yang sedang menunggu persetujuan Bank Mini:</p>

        <!-- Menampilkan daftar top-up yang sedang menunggu verifikasi -->
        <ul>
            @foreach ($topups as $topup)
                <li>
                    Jumlah: Rp{{ number_format($topup->amount, 2) }} | Status: {{ ucfirst($topup->status) }}
                </li>
            @endforeach
        </ul>

        @if($topups->isEmpty())
            <p>Anda belum memiliki top-up yang menunggu persetujuan.</p>
        @endif

    </div>

@endsection
