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
            $table->string('customer_name')->nullable()->after('type');
            $table->string('customer_phone')->nullable()->after('customer_name');
            $table->text('customer_address')->nullable()->after('customer_phone');
            $table->decimal('discount', 15, 2)->default(0)->after('total_amount');
            $table->decimal('shipping_cost', 15, 2)->default(0)->after('discount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'customer_name', 
                'customer_phone', 
                'customer_address', 
                'discount', 
                'shipping_cost'
            ]);
        });
    }
};
