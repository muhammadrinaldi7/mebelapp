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
        Schema::create('transaction_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_method_id')->constrained()->onDelete('restrict');
            $table->decimal('amount', 15, 2);
            $table->date('payment_date');
            $table->string('notes')->nullable();
            $table->timestamps();
        });

        // Migrate existing down_payment data from transactions table
        $tunaiMethod = DB::table('payment_methods')->where('name', 'Tunai')->first();

        if ($tunaiMethod) {
            $transactions = DB::table('transactions')
                ->where('type', 'sale')
                ->where('down_payment', '>', 0)
                ->get();

            foreach ($transactions as $trx) {
                DB::table('transaction_payments')->insert([
                    'transaction_id' => $trx->id,
                    'payment_method_id' => $tunaiMethod->id,
                    'amount' => $trx->down_payment,
                    'payment_date' => $trx->transaction_date,
                    'notes' => 'Migrasi otomatis dari data DP lama',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Also migrate 'lunas' transactions — the full amount was paid
            $lunasTransactions = DB::table('transactions')
                ->where('type', 'sale')
                ->where('payment_status', 'lunas')
                ->get();

            foreach ($lunasTransactions as $trx) {
                $grandTotal = ($trx->total_amount ?? 0) - ($trx->discount ?? 0) + ($trx->shipping_cost ?? 0);
                if ($grandTotal > 0) {
                    DB::table('transaction_payments')->insert([
                        'transaction_id' => $trx->id,
                        'payment_method_id' => $tunaiMethod->id,
                        'amount' => $grandTotal,
                        'payment_date' => $trx->transaction_date,
                        'notes' => 'Migrasi otomatis dari transaksi lunas',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_payments');
    }
};
