<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
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
        .card-container {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }
        .card {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            flex-grow: 1;
            text-align: center;
        }
        .card h5 {
            margin-top: 10px;
        }
        .card .btn {
            margin-top: 10px;
        }
        .dashboard-container h1 {
            font-size: 28px;
        }
        .table th, .table td {
            text-align: center;
        }
        .table th {
            background-color: #343a40;
            color: white;
        }
        .table tbody tr:hover {
            background-color: #f1f1f1;
        }
        .table td, .table th {
            vertical-align: middle;
        }
        .logout-button {
            position: absolute;
            top: 5px;
            right: 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar atas (untuk saldo) -->
    <div class="navbar-top">
        <h4>Siswa</h4>
        <div class="balance">
            Jumlah Saldo : Rp {{ number_format($user->balance, 2) }}
        </div>
    </div>

    <!-- Sidebar Navbar Samping -->
    <div class="navbar-custom">
        <a href="{{ route('bankmini.topup') }}">
            <i class="bi bi-cash-stack"></i> Top-Up
        </a>
        <a href="{{ route('students.withdraw') }}">
            <i class="bi bi-cash"></i> Tarik Tunai
        </a>
        <a href="{{ route('students.transfer') }}">
            <i class="bi bi-arrow-left-right"></i> Transfer Saldo
        </a>
    </div>

    <!-- Main Content Area -->
    <div class="content-container">
        <!-- User Info -->
        <div class="dashboard-container">
            <h1>Selamat Datang {{ Auth::user()->name }}</h1>
            <p>Login sebagai Siswa</p>
        </div>

        <!-- Cards for Actions -->
        <div class="card-container">
            <div class="card">
                <i class="bi bi-cash-stack"></i>
                <h5>Top-Up</h5>
                <p><a href="{{ route('bankmini.topup') }}" class="btn btn-success">Top-Up Sekarang</a></p>
            </div>
            <div class="card">
                <i class="bi bi-cash"></i>
                <h5>Tarik Tunai</h5>
                <p><a href="{{ route('students.withdraw') }}" class="btn btn-warning">Tarik Tunai</a></p>
            </div>
            <div class="card">
                <i class="bi bi-arrow-left-right"></i>
                <h5>Transfer</h5>
                <p><a href="{{ route('students.transfer') }}" class="btn btn-primary">Transfer Saldo</a></p>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="dashboard-container mt-4">
            <h3>Aktivitas Terakhir</h3>
            <!-- Recent Top-ups -->
            <div class="mb-4">
                <h5>Top-Up Terakhir</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topups as $topup)
                            <tr>
                                <td>{{ $topup->created_at->format('d M Y H:i') }}</td>
                                <td>Rp {{ number_format($topup->amount, 2) }}</td>
                                <td>
                                    @if($topup->status == 'pending')
                                        <span class="badge bg-warning">Menunggu</span>
                                    @elseif($topup->status == 'approved')
                                        <span class="badge bg-success">Berhasil</span>
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Withdrawals -->
            <div>
                <h5>Penarikan Terakhir</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($withdrawals as $withdrawal)
                            <tr>
                                <td>{{ $withdrawal->created_at->format('d M Y H:i') }}</td>
                                <td>Rp {{ number_format($withdrawal->amount, 2) }}</td>
                                <td>
                                    @if($withdrawal->status == 'pending')
                                        <span class="badge bg-warning">Menunggu</span>
                                    @elseif($withdrawal->status == 'approved')
                                        <span class="badge bg-success">Berhasil</span>
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Logout Button -->
        <form action="{{ route('logout') }}" method="POST" class="logout-button">
            @csrf
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
