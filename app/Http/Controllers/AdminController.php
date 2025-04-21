<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    
    public function dashboard()
    {
        // Mengambil data pengguna berdasarkan role
        $admins = User::where('role', 'admin')->get();
        $students = User::where('role', 'siswa')->get();
        $bankMinis = User::where('role', 'bank_mini')->get();

        // Mengirim data ke view dashboard
        return view('admin.dashboard', compact('admins', 'students', 'bankMinis'));
    }

    public function showTransactions()
    {
        
        $transactions = Transaction::where(function ($query) {
            
            $query->whereHas('sender', function ($query) {
                $query->where('role', 'siswa');
            })
            
            ->orWhereHas('recipient', function ($query) {
                $query->where('role', 'siswa');
            });
        })->get(); 

        return view('admin.transactions', compact('transactions'));
    }   


    
    public function manageUsers()
    {
        
        $users = User::all();

        return view('admin.users', compact('users'));
    }

    // Menyimpan pengguna baru
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed', 
        'role' => 'required|in:admin,bank_mini,siswa',
    ]);

    try {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password), 
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users')->with('success', 'User berhasil dibuat!');
    } catch (\Exception $e) {
        return redirect()->route('admin.users')->with('error', 'Gagal membuat user. Coba lagi.');
    }
}


public function printTransactions()
{
    $transactions = Transaction::where(function ($query) {
        $query->whereHas('sender', function ($query) {
            $query->where('role', 'siswa');
        })->orWhereHas('recipient', function ($query) {
            $query->where('role', 'siswa');
        });
    })->get();

    $pdf = PDF::loadView('admin.transactions_pdf', compact('transactions'));

    return $pdf->download('transaksi_siswa.pdf'); 
    
}

// edit user
public function edit(User $user)
    {
        return view('admin.edit_user', compact('user'));
    }

// menyimpan data role
public function update(Request $request, User $user)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'password' => 'nullable|string|min:8|confirmed',
        'role' => 'required|in:admin,bank_mini,siswa',
    ]);

    try {
        // Update nama, email, password dan role
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->role = $request->role;
        $user->save();

        return redirect()->route('admin.users')->with('success', 'User berhasil diperbarui!');
    } catch (\Exception $e) {
        return redirect()->route('admin.users')->with('error', 'Gagal memperbarui user. Coba lagi.');
    }
}
public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('admin.users')->with('success', 'User berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.users')->with('error', 'Gagal menghapus user. Coba lagi.');
        }
    }
}
