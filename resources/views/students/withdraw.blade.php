<!-- views/students/withdraw.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarik Tunai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .withdrawal-form {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .balance-info {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="withdrawal-form">
            <h2 class="form-title">Tarik Tunai</h2>
            
            <div class="balance-info">
                <h5>Saldo Anda</h5>
                <h3>Rp {{ number_format(Auth::user()->balance, 2) }}</h3>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('students.withdraw.process') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="amount" class="form-label">Jumlah Penarikan (Rp)</label>
                    <input type="number" 
                           class="form-control" 
                           id="amount" 
                           name="amount" 
                           min="10000" 
                           step="10000" 
                           required>
                    <div class="form-text">Minimal penarikan Rp 10.000</div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Keterangan (Opsional)</label>
                    <textarea class="form-control" 
                              id="description" 
                              name="description" 
                              rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100">Ajukan Penarikan</button>
            </form>

            <a href="{{ route('students.dashboard') }}" class="btn btn-link w-100 mt-3">
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</body>
</html>