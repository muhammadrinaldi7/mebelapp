<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default payment methods
        DB::table('payment_methods')->insert([
            ['name' => 'Tunai', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Transfer BCA', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Transfer BNI', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Transfer Mandiri', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Transfer BRI', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'QRIS', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kartu Debit/Kredit', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
