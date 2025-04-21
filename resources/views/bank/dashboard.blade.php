<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Bank Mini</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .navbar-top {
            background-color: #343a40;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
        }
        .navbar-top h4 {
            margin: 0;
        }
        .navbar-top .balance {
            font-size: 18px;
            text-align: center;
            flex-grow: 1;
        }
        .navbar-custom {
            background-color: #343a40;
            padding: 20px;
            position: fixed;
            height: 100%;
            top: 0;
            left: 0;
            width: 220px;
            color: white;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        .navbar-custom a {
            color: white;
            text-decoration: none;
            margin: 15px 0;
            display: block;
            padding: 10px;
            border-radius: 5px;
        }
        .navbar-custom a:hover {
            background-color: #495057;
        }
        .content-container {
            margin-left: 240px;
            padding: 20px;
        }
        .dashboard-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 1000px;
            margin-bottom: 30px;
        }
        .dashboard-container h1 {
            color: #333333;
            margin-bottom: 20px;
        }
        .dashboard-container p {
            color: #555555;
        }
        .topup-list, .withdrawal-list, .transfer-list {
            margin-top: 20px;
        }
        .topup-list table, .withdrawal-list table, .transfer-list table {
            width: 100%;
            border-collapse: collapse;
        }
        .topup-list th, .withdrawal-list th, .transfer-list th, 
        .topup-list td, .withdrawal-list td, .transfer-list td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .topup-list th, .withdrawal-list th, .transfer-list th {
            background-color: #343a40;
            color: white;
        }
        .action-btns button {
            margin: 5px;
        }
        .logout-button {
            position: absolute;
            top: 5px;
            right: 20px;
        }
        .topup-form-container {
            margin-top: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="navbar-top">
        <h4>Bank Mini</h4>
    </div>
    <div class="navbar-custom">
        <a href="{{ route('bankmini.transaction') }}">
            <i class="bi bi-arrow-left-right"></i> Riwayat Transaksi
        </a>
        <a href="{{ route('bankmini.users') }}">
            <i class="bi bi-people"></i> Tambah User
        </a>
    </div>

    <div class="content-container">
        <div class="dashboard-container">
            <h1>Selamat Datang {{ Auth::user()->name }}</h1>
            <p>Login sebagai Bank Mini</p>
        </div>

        
        <div class="topup-form-container">
            <h3>Top-Up Saldo Siswa</h3>
            <form action="{{ route('bankmini.topupToStudent') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="user_name" class="form-label">Nama Siswa</label>
                    <input type="text" class="form-control" id="user_name" name="user_name" required>
                </div>
                <div class="mb-3">
                    <label for="amount" class="form-label">Jumlah Top-Up (IDR)</label>
                    <input type="number" class="form-control" id="amount" name="amount" min="1000" required>
                </div>
                <button type="submit" class="btn btn-primary">Top-Up</button>
            </form>
        </div>  

        <div class="topup-list">
            <h3>Daftar Top-Up yang Diajukan</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Jumlah Top-Up</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topups as $topup)
                    <tr>
                        <td>{{ $topup->user->name }}</td>
                        <td>{{ number_format($topup->amount, 2) }} IDR</td>
                        <td>
                            @if($topup->status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($topup->status == 'accepted')
                                <span class="badge bg-success">Terima</span>
                            @else
                                <span class="badge bg-danger">Tolak</span>
                            @endif
                        </td>
                        <td class="action-btns">
                            @if($topup->status == 'pending')
                            <form action="{{ route('bankmini.topup.accept', $topup->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success">Terima</button>
                            </form>
                            <form action="{{ route('bankmini.topup.reject', $topup->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger">Tolak</button>
                            </form>
                            @else
                                <button class="btn btn-secondary" disabled>Aksi Tidak Tersedia</button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Daftar Penarikan Tunai yang Diajukan -->
        <div class="withdrawal-list mt-4">
            <h3>Daftar Penarikan Tunai yang Diajukan</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Jumlah Penarikan</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($withdrawals as $withdrawal)
                    <tr>
                        <td>{{ $withdrawal->user->name }}</td>
                        <td>Rp {{ number_format($withdrawal->amount, 2) }}</td>
                        <td>{{ $withdrawal->description ?: '-' }}</td>
                        <td>
                            @if($withdrawal->status == 'pending')
                                <span class="badge bg-warning">Menunggu</span>
                            @elseif($withdrawal->status == 'approved')
                                <span class="badge bg-success">Disetujui</span>
                            @else
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </td>
                        <td class="action-btns">
                            @if($withdrawal->status == 'pending')
                            <form action="{{ route('students.withdraw.approve', $withdrawal->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                            </form>
                            <form action="{{ route('students.withdraw.reject', $withdrawal->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                            </form>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>Selesai</button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Daftar Transfer Antar Siswa -->
        <div class="transfer-list mt-4">
            <h3>Daftar Transfer Antar Siswa</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Pengirim</th>
                        <th>Penerima</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transfers as $transfer)
                    <tr>
                        <td>{{ $transfer->sender->name }}</td>
                        <td>{{ $transfer->recipient->name }}</td>
                        <td>Rp {{ number_format($transfer->amount, 2) }}</td>
                        <td>
                            @if($transfer->status == 'pending')
                                <span class="badge bg-warning">Menunggu</span>
                            @elseif($transfer->status == 'approved')
                                <span class="badge bg-success">Disetujui</span>
                            @else
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </td>
                        <td>
                            @if($transfer->status == 'pending')
                                <form action="{{ route('bankmini.transfer.approve', $transfer->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                                </form>
                                <form action="{{ route('bankmini.transfer.reject', $transfer->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                </form>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>Selesai</button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <form action="{{ route('logout') }}" method="POST" class="logout-button">
            @csrf
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
