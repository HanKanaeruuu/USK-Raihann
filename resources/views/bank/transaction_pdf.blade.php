<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi Siswa - Bank Mini</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #333;
            padding: 6px 8px;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 40px;
            color: #777;
        }
    </style>
</head>
<body>
    <h2>Laporan Transaksi Siswa - Bank Mini</h2>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Pengirim</th>
                <th>Penerima</th>
                <th>Jumlah</th>
                <th>Jenis</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $transaction->sender ? $transaction->sender->name : 'N/A' }}</td>
                    <td>{{ $transaction->recipient ? $transaction->recipient->name : 'N/A' }}</td>
                    <td>Rp {{ number_format($transaction->amount, 2, ',', '.') }}</td>
                    <td>{{ $transaction->type }}</td>
                    <td>{{ $transaction->status }}</td>
                    <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}
    </div>
</body>
</html>
