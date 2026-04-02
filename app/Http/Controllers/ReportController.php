<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function export(Request $request)
    {
        $type = $request->get('type', 'pdf'); // pdf or excel
        $tab = $request->get('tab', 'transactions'); // transactions, stock, movement, profit

        // Common filters
        $search = $request->get('search');
        $from = $request->get('from');
        $to = $request->get('to');
        
        $data = [
            'tab' => $tab,
            'dateFrom' => $from,
            'dateTo' => $to,
            'exportType' => $type
        ];

        // Fetch Data based on tab
        if ($tab === 'transactions') {
            $rt = $request->get('rt', 'all');
            $data['transactions'] = Transaction::with('user', 'details.product')
                ->when($rt !== 'all', fn($q) => $q->where('type', $rt))
                ->when($from, fn($q) => $q->whereDate('transaction_date', '>=', $from))
                ->when($to, fn($q) => $q->whereDate('transaction_date', '<=', $to))
                ->when($search, fn($q) => $q->where('reference_code', 'like', '%' . $search . '%'))
                ->latest('transaction_date')
                ->get();
            $data['reportTitle'] = 'Log Transaksi';
        }
        elseif ($tab === 'stock') {
            $cid = $request->get('cid');
            $bid = $request->get('bid');
            $data['products'] = Product::with('category', 'brand')
                ->when($cid, fn($q) => $q->where('category_id', $cid))
                ->when($bid, fn($q) => $q->where('brand_id', $bid))
                ->when($search, fn($q) => $q->where('name', 'like', '%' . $search . '%')->orWhere('sku', 'like', '%' . $search . '%'))
                ->orderBy('current_stock', 'asc')
                ->get();
            $data['reportTitle'] = 'Stok & Valuasi Aset';
        }
        elseif ($tab === 'movement') {
            $subIn = TransactionDetail::select('product_id', DB::raw('SUM(quantity) as qty_in'))
                ->whereHas('transaction', fn($q) => $q->where('type', 'in')
                    ->when($from, fn($q2) => $q2->whereDate('transaction_date', '>=', $from))
                    ->when($to, fn($q2) => $q2->whereDate('transaction_date', '<=', $to)))
                ->groupBy('product_id');

            $subOut = TransactionDetail::select('product_id', DB::raw('SUM(quantity) as qty_out'))
                ->whereHas('transaction', fn($q) => $q->where('type', 'out')
                    ->when($from, fn($q2) => $q2->whereDate('transaction_date', '>=', $from))
                    ->when($to, fn($q2) => $q2->whereDate('transaction_date', '<=', $to)))
                ->groupBy('product_id');

            $subSale = TransactionDetail::select('product_id', DB::raw('SUM(quantity) as qty_sale'))
                ->whereHas('transaction', fn($q) => $q->where('type', 'sale')
                    ->when($from, fn($q2) => $q2->whereDate('transaction_date', '>=', $from))
                    ->when($to, fn($q2) => $q2->whereDate('transaction_date', '<=', $to)))
                ->groupBy('product_id');

            $data['productsMove'] = Product::with('category', 'brand')
                ->leftJoinSub($subIn, 't_in', function ($join) { $join->on('products.id', '=', 't_in.product_id'); })
                ->leftJoinSub($subOut, 't_out', function ($join) { $join->on('products.id', '=', 't_out.product_id'); })
                ->leftJoinSub($subSale, 't_sale', function ($join) { $join->on('products.id', '=', 't_sale.product_id'); })
                ->select('products.*', 
                    DB::raw('COALESCE(t_in.qty_in, 0) as total_in'),
                    DB::raw('COALESCE(t_out.qty_out, 0) as total_out'),
                    DB::raw('COALESCE(t_sale.qty_sale, 0) as total_sale')
                )
                ->when($search, fn($q) => $q->where('name', 'like', '%' . $search . '%')->orWhere('sku', 'like', '%' . $search . '%'))
                ->orderByRaw('COALESCE(t_sale.qty_sale, 0) DESC')
                ->get();
            $data['reportTitle'] = 'Fast & Slow Moving (Pergerakan Barang)';
        }
        elseif ($tab === 'profit') {
            $data['profitDetails'] = TransactionDetail::with('product', 'transaction')
                ->whereHas('transaction', function($q) use ($from, $to) {
                    $q->where('type', 'sale')
                      ->when($from, fn($q2) => $q2->whereDate('transaction_date', '>=', $from))
                      ->when($to, fn($q2) => $q2->whereDate('transaction_date', '<=', $to));
                })
                ->when($search, fn($q) => $q->whereHas('product', fn($q2) => $q2->where('name', 'like', '%' . $search . '%')->orWhere('sku', 'like', '%' . $search . '%')))
                ->latest('created_at')
                ->get();
            $data['reportTitle'] = 'Keuntungan Kotor Penjualan';
        }

        $filename = 'laporan_' . $tab . '_' . date('Ymd_His');

        if ($type === 'excel') {
            return Excel::download(new ReportExport('reports.excel', $data), $filename . '.xlsx');
        }

        // Output as PDF via DomPDF
        $pdf = Pdf::loadView('reports.pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream($filename . '.pdf');
    }
}
