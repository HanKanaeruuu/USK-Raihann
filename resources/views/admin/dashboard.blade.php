<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        
        .navbar-custom {
            display: flex;
            flex-direction: column;
            background-color: #343a40;
            padding: 20px;
            position: fixed;
            height: 100%;
            top: 0;
            left: 0;
            width: 200px;
            color: white;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .navbar-custom h4 {
            margin-bottom: 30px;
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
            margin-left: 220px;
            padding: 20px;
        }

        .dashboard-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 1000px;
            width: 100%;
            margin-bottom: 30px;
        }

        .dashboard-container h1 {
            color: #333333;
            margin-bottom: 20px;
        }

        .dashboard-container p {
            color: #555555;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .card {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            width: 30%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .card h5 {
            color: #333333;
        }

        .card-admin {
            background-color: #d1e7dd;
        }

        .card-student {
            background-color: #f8d7da;
        }

        .card-bank {
            background-color: #cce5ff;
        }

        .card p {
            color: #555555;
            font-weight: bold;
        }

        .card i {
            font-size: 30px;
            margin-bottom: 10px;
        }

        .logout-button {
            position: fixed;
            top: 20px;
            right: 20px;
        }

    </style>
</head>
<body>

    <div class="navbar-custom">
        <h4>Admin</h4>
        <a href="{{ route('admin.transactions') }}">
            <i class="bi bi-wallet2"></i> Lihat Transaksi
        </a>
        <a href="{{ route('admin.users') }}">
            <i class="bi bi-people"></i> Tambar User
        </a>
    </div>

    
    <div class="content-container">

        <!-- User Info -->
        <div class="dashboard-container">
            <h1>Selamat Datang {{ Auth::user()->name }}</h1>
            <p>Login Sebagai Admin.</p>
        </div>

        <div class="card-container">

            <div class="card card-admin">
                <i class="bi bi-person-badge"></i>
                <h5>Admin</h5>
                <p>{{ optional($admins)->count() }} Admin</p>
            </div>

            <div class="card card-student">
                <i class="bi bi-person"></i>
                <h5>Students</h5>
                <p>{{ optional($students)->count() }} Student</p>
            </div>

            <div class="card card-bank">
                <i class="bi bi-bank"></i>
                <h5>Bank Mini</h5>
                <p>{{ optional($bankMinis)->count() }} Bank Mini</p>
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
