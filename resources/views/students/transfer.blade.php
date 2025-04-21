<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Saldo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Transfer Saldo ke Siswa Lain</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('students.transfer.process') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="recipient_id" class="form-label">Pilih Penerima</label>
                                <select class="form-select" name="recipient_id" required>
                                    <option value="">Pilih Siswa</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}">{{ $student->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="amount" class="form-label">Jumlah Transfer</label>
                                <input type="number" class="form-control" name="amount" min="1000" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Kirim</button>
                            <a href="{{ route('students.dashboard') }}" class="btn btn-secondary">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
