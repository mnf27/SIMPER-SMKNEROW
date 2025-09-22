<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->string('no_induk')->unique();
            $table->string('judul');
            $table->string('penulis');
            $table->string('penerbit');
            $table->year('tahun_terbit');
            $table->string('cetakan_edisi')->nullable();
            $table->string('klasifikasi')->nullable();
            $table->foreignId('id_kategori')->constrained('kategori')->onDelete('cascade');
            $table->integer('jumlah_eksemplar');
            $table->string('asal')->nullable();
            $table->decimal('harga', 12, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->string('cover_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
