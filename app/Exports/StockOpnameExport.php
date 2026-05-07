<?php

namespace App\Exports;

use App\Models\StockOpname;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StockOpnameExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $opnames;

    public function __construct($opnames)
    {
        $this->opnames = $opnames;
    }

    public function view(): View
    {
        return view('reports.excel.stock_opname', [
            'opnames' => $this->opnames
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
