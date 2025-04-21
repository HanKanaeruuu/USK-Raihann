<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TopUp;
use App\Models\Transaction;
use App\Models\Withdrawal;

class StudentController extends Controller
{

    public function dashboard()
    {
        // Ambil data user yang sedang login
        $user = auth()->user();
        
        // Ambil riwayat withdrawal dan topup untuk siswa yang login
        $withdrawals = Withdrawal::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $topups = TopUp::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('students.dashboard', compact('user', 'withdrawals', 'topups'));
    }

    // Menampilkan form top-up
    public function topUpForm()
    {
        return view('students.topup');
    }

    // Memproses top-up saldo
    public function processTopUp(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1000',
        ]);

        // Ambil data pengguna yang sedang login
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('students.topup')->with('error', 'Pengguna tidak terautentikasi.');
        }

        // Simpan transaksi top-up dengan status "pending"
        $topup = new Topup();
        $topup->user_id = $user->id;
        $topup->amount = $request->amount;
        $topup->status = 'pending';  // Status awal menunggu persetujuan
        $topup->save();

        return redirect()->route('students.topup')->with('success', 'Top-Up saldo Anda sedang menunggu persetujuan.');
    }

    // Menyetujui top-up dan menambah saldo pengguna
    public function approveTopUp($topupId)
    {
        $topup = Topup::find($topupId);

        if (!$topup || $topup->status !== 'pending') {
            return redirect()->route('bank.dashboard')->with('error', 'Top-Up tidak ditemukan atau sudah diproses.');
        }

        // Perbarui status top-up menjadi 'approved'
        $topup->status = 'approved';
        $topup->save();

        // Tambahkan saldo pada pengguna
        $user = User::find($topup->user_id);
        $user->balance += $topup->amount;
        $user->save();

        // Simpan transaksi ke tabel transaksi
        Transaction::create([
            'sender_id' => null,
            'recipient_id' => $user->id,
            'amount' => $topup->amount,
            'type' => 'Top-Up',
            'status' => 'Berhasil',
        ]);

        return redirect()->route('bank.dashboard')->with('success', 'Top-Up berhasil disetujui dan saldo telah ditambahkan.');
    }
    
    public function rejectTopUp($id)
{
    $topup = TopUp::findOrFail($id);
    $topup->status = 'rejected';
    $topup->save();

    return redirect()->route('bankmini.dashboard')->with('success', 'Top-up ditolak.');
}


    public function withdrawForm()
{
    return view('students.withdraw');
}

public function processWithdraw(Request $request)
{
    $request->validate([
        'amount' => 'required|numeric|min:10000',
        'description' => 'nullable|string|max:255',
    ]);

    $user = auth()->user();
    
    if ($user->balance < $request->amount) {
        return back()->with('error', 'Saldo tidak mencukupi untuk melakukan penarikan.');
    }

    // Create withdrawal request
    $withdrawal = new Withdrawal([
        'user_id' => $user->id,
        'amount' => $request->amount,
        'description' => $request->description,
        'status' => 'pending'
    ]);
    
    $withdrawal->save();

    return redirect()->route('students.dashboard')
        ->with('success', 'Permintaan penarikan tunai telah diajukan dan menunggu persetujuan.');
}

public function approveWithdraw($id)
{
    $withdrawal = Withdrawal::findOrFail($id);

    // Change status to 'approved'
    $withdrawal->status = 'approved';
    $withdrawal->save();

    // Update user balance after withdrawal (example logic)
    $user = $withdrawal->user;
    $user->balance -= $withdrawal->amount;
    $user->save();

    Transaction::create([
        'sender_id' => $user->id,
        'recipient_id' => null, 
        'amount' => $withdrawal->amount,
        'type' => 'Tarik Tunai',
        'status' => 'approved',
    ]);
    

    // Redirect to bank dashboard with success message
    return redirect()->route('bankmini.dashboard')->with('success', 'Withdrawal approved.');
}


public function rejectWithdraw($id)
{
    $withdrawal = Withdrawal::findOrFail($id);
    $withdrawal->status = 'rejected';
    $withdrawal->save();

    return back()->with('success', 'Penarikan tunai telah ditolak.');
}

 public function transferForm()
    {
        // Get all students except the current user
        $students = User::where('role', 'siswa')
            ->where('id', '!=', auth()->id())
            ->get();
        return view('students.transfer', compact('students'));
    }

    public function processTransfer(Request $request)
    {
        // Validasi data transfer
        $request->validate([
            'recipient_id' => 'required|exists:users,id|different:sender_id',  // Pastikan penerima bukan pengirim
            'amount' => 'required|numeric|min:1000',  // Jumlah transfer minimal 1000
        ]);
    
        $sender = auth()->user();  // Pengirim (siswa yang sedang login)
        $recipient = User::find($request->recipient_id);  // Mencari penerima berdasarkan ID
    
        // Pastikan saldo pengirim cukup
        if ($sender->balance < $request->amount) {
            return back()->with('error', 'Saldo tidak mencukupi.');
        }
    
        // Simpan transaksi sebagai "pending"
        $transaction = new Transaction();
        $transaction->sender_id = $sender->id;
        $transaction->recipient_id = $recipient->id;
        $transaction->amount = $request->amount;
        $transaction->type = 'Transfer';
        $transaction->status = 'pending';  // Status transfer awal
        $transaction->save();
    
        // Kembalikan pesan bahwa transfer sedang menunggu persetujuan
        return redirect()->route('students.dashboard')->with('success', 'Transfer sedang menunggu persetujuan dari Bank Mini.');
    }    

    public function processTransaction(Request $request)
{
    $request->validate([
        'recipient_id' => 'required|exists:users,id',
        'amount' => 'required|numeric|min:1',
    ]);

    $sender = auth()->user();
    $recipient = User::find($request->recipient_id);

    if ($sender->balance < $request->amount) {
        return back()->with('error', 'Saldo tidak mencukupi.');
    }

    // Kurangi saldo pengirim dan tambahkan saldo penerima
    $sender->balance -= $request->amount;
    $recipient->balance += $request->amount;
    $sender->save();
    $recipient->save();

    // Simpan transaksi
    Transaction::create([
        'sender_id' => $sender->id,
        'recipient_id' => $recipient->id,
        'amount' => $request->amount,
        'type' => 'Transfer',
        'status' => 'Berhasil',
    ]);

    return redirect()->route('students.dashboard')->with('success', 'Transaksi berhasil dilakukan.');
}

}
