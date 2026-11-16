
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pensiunan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('nip')->unique();
            $table->string('nik')->unique();
            $table->string('nama');
            $table->string('jenis_kelamin', 20);
            $table->date('tanggal_lahir');
            $table->date('tanggal_mulai_kerja')->nullable();
            $table->date('tanggal_pensiun');

            $table->string('jabatan_terakhir');
            $table->string('unit_kerja')->nullable();
            $table->text('alamat');
            $table->string('no_telepon', 20);

            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pensiunan');
    }
};
