<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function invoice($id)
    {
        $transaction = Transaction::with(['user', 'details.product'])->findOrFail($id);
        
        // Ensure it is a sale transaction
        if ($transaction->type !== 'sale') {
            abort(404, 'Bukan transaksi penjualan.');
        }

        return view('sales.invoice', compact('transaction'));
    }
}
