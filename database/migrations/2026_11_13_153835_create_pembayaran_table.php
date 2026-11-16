
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pensiunan_id')
                  ->constrained('pensiunan')
                  ->cascadeOnDelete();

            $table->foreignId('processed_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->string('periode', 7); // Format: YYYY-MM
            $table->date('tanggal_pembayaran')->nullable();

            $table->decimal('jumlah', 15, 2);
            $table->enum('status', [
                'menunggu',
                'diproses',
                'berhasil',
                'gagal'
            ])->default('menunggu');

            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['pensiunan_id', 'periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
