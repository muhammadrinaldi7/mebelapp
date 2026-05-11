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
        // Add is_preorder flag to transactions
        Schema::table('transactions', function (Blueprint $table) {
            $table->boolean('is_preorder')->default(false)->after('shipping_status');
        });

        // Make product_id nullable and add custom_product_name for PO items
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->string('custom_product_name')->nullable()->after('product_id');

            // Drop foreign key first, then modify column
            $table->dropForeign(['product_id']);
            $table->unsignedBigInteger('product_id')->nullable()->change();

            // Re-add foreign key with nullable support
            $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('is_preorder');
        });

        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn('custom_product_name');
            $table->unsignedBigInteger('product_id')->nullable(false)->change();
            $table->foreign('product_id')->references('id')->on('products');
        });
    }
};
