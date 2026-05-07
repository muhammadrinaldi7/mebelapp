<?php

namespace App\Http\Controllers;

use App\Models\StockOpname;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StockOpnameExport;

class StockOpnameController extends Controller
{
    public function export(Request $request)
    {
        $type = $request->get('type', 'pdf');
        $search = $request->get('search');

        $opnames = StockOpname::with('user', 'details.product')
            ->where('reference_code', 'like', '%' . $search . '%')
            ->orWhereHas('user', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })
            ->latest('opname_date')
            ->get();

        $data = [
            'opnames' => $opnames,
            'reportTitle' => 'Laporan Riwayat Stock Opname',
        ];

        $filename = 'Laporan_Stock_Opname_' . date('Ymd_His');

        if ($type === 'excel') {
            return Excel::download(new StockOpnameExport($opnames), $filename . '.xlsx');
        }

        // Export as PDF
        $pdf = Pdf::loadView('reports.stock_opname_pdf', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream($filename . '.pdf');
    }

    public function detailPdf($id)
    {
        $opname = StockOpname::with('user', 'details.product')->findOrFail($id);
        
        $data = [
            'opname' => $opname,
            'reportTitle' => 'Detail Stock Opname: ' . $opname->reference_code,
        ];

        $pdf = Pdf::loadView('reports.stock_opname_detail_pdf', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream($opname->reference_code . '.pdf');
    }
}
