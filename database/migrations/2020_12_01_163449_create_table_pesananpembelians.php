<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePesananpembelians extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_pos', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
        });

        Schema::create('pesanan_pembelians', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->date('tanggal');
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('kategoripesanan_id');
            $table->unsignedBigInteger('komoditas_id');
            $table->string('no_so')->nullable();
            $table->double('top')->nullable();
            $table->unsignedBigInteger('status_po_id');
            $table->string('keterangan')->nullable();
            $table->double('subtotal')->nullable();
            $table->double('total_diskon_detail')->nullable();
            $table->double('total')->nullable();

            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('created_by')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('updated_by')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('deleted_by')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('supplier_id')
                ->references('id')->on('suppliers')
                ->onDelete('cascade');

            $table->foreign('kategoripesanan_id')
                ->references('id')->on('kategoripesanans')
                ->onDelete('cascade');

            $table->foreign('komoditas_id')
                ->references('id')->on('komoditas')
                ->onDelete('cascade');

            $table->foreign('status_po_id')
                ->references('id')->on('status_pos')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('status_pos');
        Schema::dropIfExists('pesanan_pembelians');
    }
}
