<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin@example.com'),  
                'role' => 'admin',
            ],
            [
                'name' => 'BankMini',
                'email' => 'bankmini@example.com',
                'password' => Hash::make('bankmini@example.com'), 
                'role' => 'bank_mini',
            ],
            [
                'name' => 'Siswa',
                'email' => 'siswa@example.com',
                'password' => Hash::make('siswa@example.com'),   
                'role' => 'siswa',
            ],
        ];

        
        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']], 
                $user                         
            );
        }
    }
}
