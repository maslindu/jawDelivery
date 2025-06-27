<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Ubah kolom status menjadi VARCHAR yang lebih panjang
            $table->string('status', 50)->change();
            
            // Atau jika menggunakan ENUM, tambahkan nilai baru
            // $table->enum('status', [
            //     'pending', 
            //     'processing', 
            //     'shipped', 
            //     'on_delivery',  // Tambahkan ini
            //     'delivered', 
            //     'cancelled'
            // ])->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status', 20)->change();
        });
    }
};
