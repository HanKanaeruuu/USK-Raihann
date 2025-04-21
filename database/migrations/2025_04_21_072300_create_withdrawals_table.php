<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 10, 2); // Untuk menyimpan jumlah penarikan dengan 2 desimal
            $table->text('description')->nullable(); // Untuk menyimpan keterangan penarikan (opsional)
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status penarikan
            $table->timestamp('processed_at')->nullable(); // Waktu ketika penarikan diproses
            $table->foreignId('processed_by')->nullable()->constrained('users'); // Admin/Bank yang memproses
            $table->timestamps(); // membuat
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};