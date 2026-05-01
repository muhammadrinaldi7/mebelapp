<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\DotMatrixPrinterService;
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

    public function deliveryNote($id)
    {
        $transaction = Transaction::with(['user', 'details.product'])->findOrFail($id);
        
        // Ensure it is a sale transaction
        if ($transaction->type !== 'sale') {
            abort(404, 'Bukan transaksi penjualan.');
        }

        return view('sales.delivery_note', compact('transaction'));
    }

    /**
     * Generate raw ESC/P text as base64 for dot matrix printing (Epson LX-310).
     * Returns JSON with base64 data for use with RawBT URI intent on Android.
     */
    public function printRaw($id)
    {
        $transaction = Transaction::with(['user', 'details.product'])->findOrFail($id);

        if ($transaction->type !== 'sale') {
            abort(404, 'Bukan transaksi penjualan.');
        }

        $service = new DotMatrixPrinterService();
        $base64  = $service->generateInvoiceBase64($transaction);

        return response()->json([
            'success'        => true,
            'reference_code' => $transaction->reference_code,
            'base64'         => $base64,
        ]);
    }

    /**
     * Preview dot matrix invoice output in browser.
     * Shows how the invoice will look when printed on LX-310.
     */
    public function dotmatrixPreview($id)
    {
        $transaction = Transaction::with(['user', 'details.product'])->findOrFail($id);

        if ($transaction->type !== 'sale') {
            abort(404, 'Bukan transaksi penjualan.');
        }

        $service = new DotMatrixPrinterService();
        $raw     = $service->generateInvoiceRaw($transaction);
        $base64  = base64_encode($raw);

        // Strip ESC/P control codes for text preview (keep only printable chars)
        $preview = preg_replace('/\x1B[^\x40-\x7E]*[\x40-\x7E]/', '', $raw);
        $preview = str_replace(["\x0C", "\x0D"], '', $preview);

        return view('sales.dotmatrix_preview', compact('transaction', 'preview', 'base64'));
    }
}
