
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verifikasi_data', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pensiunan_id')
                  ->constrained('pensiunan')
                  ->cascadeOnDelete();

            $table->foreignId('diverifikasi_oleh')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->enum('status', [
                'menunggu',
                'diterima',
                'ditolak'
            ])->default('menunggu');

            $table->dateTime('tanggal_verifikasi')->nullable();
            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifikasi_data');
    }
};
