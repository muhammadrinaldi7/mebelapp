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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('payment_status')->default('lunas'); // lunas, dp, belum dibayar
            $table->decimal('down_payment', 15, 2)->default(0);
            $table->string('shipping_status')->default('bawa_sendiri'); // bawa_sendiri, menunggu_dikirim, sedang_dikirim, sudah_diterima
            $table->string('driver_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'down_payment', 'shipping_status', 'driver_name']);
        });
    }
};
