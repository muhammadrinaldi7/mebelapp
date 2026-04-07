<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionOutController extends Controller
{
    public function print($id)
    {
        $transaction = Transaction::with(['user', 'details.product'])->findOrFail($id);
        
        // Ensure it is an 'out' transaction
        if ($transaction->type !== 'out') {
            abort(404, 'Bukan transaksi pengeluaran stok.');
        }

        return view('transactions.out_print', compact('transaction'));
    }
}
