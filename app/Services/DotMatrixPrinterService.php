<?php

namespace App\Services;

use App\Models\Transaction;

class DotMatrixPrinterService
{
    // ─── ESC/P Control Codes ────────────────────────────────────
    const ESC = "\x1B";       // Escape character
    const LF  = "\x0A";       // Line Feed
    const CR  = "\x0D";       // Carriage Return
    const FF  = "\x0C";       // Form Feed (eject page)

    // Max printable columns for 10 CPI on 9.5" paper
    const MAX_COLS = 80;

    /**
     * Generate raw ESC/P text for an invoice, returned as base64.
     */
    public function generateInvoiceBase64(Transaction $transaction): string
    {
        $raw = $this->generateInvoiceRaw($transaction);
        return base64_encode($raw);
    }

    /**
     * Generate raw ESC/P byte stream for a sales invoice.
     */
    public function generateInvoiceRaw(Transaction $transaction): string
    {
        $out = '';

        // ── Initialize Printer ──
        $out .= self::ESC . "@";                    // Reset printer
        $out .= self::ESC . "x" . "\x00";          // Draft mode (fast)
        $out .= self::ESC . "M" . "\x00";          // 12 CPI (Pica)
        $out .= self::ESC . "2";                    // 1/6 inch line spacing

        // ══════════════════════════════════════════════════════════
        //  HEADER TOKO
        // ══════════════════════════════════════════════════════════
        $out .= self::ESC . "E";                    // Bold ON
        $out .= $this->centerText(strtoupper(config('app.name', 'MEBEL STOCK')), self::MAX_COLS);
        $out .= self::ESC . "F";                    // Bold OFF
        $out .= $this->centerText("Retail Furniture", self::MAX_COLS);
        $out .= self::CR . self::LF;

        // ── Separator ──
        $out .= str_repeat("=", self::MAX_COLS) . self::CR . self::LF;

        // ══════════════════════════════════════════════════════════
        //  TITLE: NOTA PENJUALAN
        // ══════════════════════════════════════════════════════════
        $out .= self::ESC . "E";                    // Bold ON
        $out .= $this->centerText("N O T A   P E N J U A L A N", self::MAX_COLS);
        $out .= self::ESC . "F";                    // Bold OFF
        $out .= str_repeat("=", self::MAX_COLS) . self::CR . self::LF;

        // ══════════════════════════════════════════════════════════
        //  INFO NOTA & PELANGGAN (2-column layout)
        // ══════════════════════════════════════════════════════════
        $leftCol = 40;
        $rightCol = self::MAX_COLS - $leftCol;

        $customerName = $transaction->customer_name ?: 'Bpk/Ibu (Umum)';
        $paymentLabel = match ($transaction->payment_status) {
            'dp'            => 'DP (Hutang)',
            'lunas'         => 'LUNAS',
            'belum_dibayar' => 'Belum Dibayar',
            default         => $transaction->payment_status,
        };
        $shippingLabel = str_replace('_', ' ', ucwords(str_replace('_', '_', $transaction->shipping_status), '_'));

        $leftLines = [
            "Kepada Yth  : {$customerName}",
        ];
        if ($transaction->customer_phone) {
            $leftLines[] = "Telp/WA     : {$transaction->customer_phone}";
        }
        if ($transaction->customer_address) {
            $leftLines[] = "Alamat      : " . mb_substr($transaction->customer_address, 0, 26);
        }

        $rightLines = [
            "Kode  : {$transaction->reference_code}",
            "Tgl   : {$transaction->transaction_date->format('d/m/Y')}",
            "Bayar : {$paymentLabel}",
            "Kirim : {$shippingLabel}",
        ];
        if ($transaction->driver_name) {
            $rightLines[] = "Supir : {$transaction->driver_name}";
        }

        $maxRows = max(count($leftLines), count($rightLines));
        for ($i = 0; $i < $maxRows; $i++) {
            $left  = $leftLines[$i]  ?? '';
            $right = $rightLines[$i] ?? '';
            $out .= $this->twoColumnLine($left, $right, $leftCol, $rightCol);
        }

        if ($transaction->notes) {
            $out .= "Keterangan  : {$transaction->notes}" . self::CR . self::LF;
        }

        // ══════════════════════════════════════════════════════════
        //  TABEL ITEM
        // ══════════════════════════════════════════════════════════
        $out .= str_repeat("-", self::MAX_COLS) . self::CR . self::LF;

        // Header tabel: No | SKU | Nama Item | Qty | Harga | Total
        $out .= self::ESC . "E";
        $out .= sprintf(
            "%-3s %-12s %-31s %4s %13s %13s",
            "No", "SKU", "Nama Item", "Qty", "Harga", "Total"
        ) . self::CR . self::LF;
        $out .= self::ESC . "F";

        $out .= str_repeat("-", self::MAX_COLS) . self::CR . self::LF;

        // Rows
        foreach ($transaction->details as $i => $detail) {
            $sku   = mb_substr($detail->product->sku ?? '-', 0, 12);
            $name  = mb_substr($detail->product->name ?? '-', 0, 31);
            $qty   = $detail->quantity;
            $price = $detail->price_at_transaction;
            $total = $qty * $price;

            $out .= sprintf(
                "%-3s %-12s %-31s %4s %13s %13s",
                ($i + 1),
                $sku,
                $name,
                $qty,
                number_format($price, 0, ',', '.'),
                number_format($total, 0, ',', '.')
            ) . self::CR . self::LF;
        }

        $out .= str_repeat("-", self::MAX_COLS) . self::CR . self::LF;

        // ══════════════════════════════════════════════════════════
        //  TOTALS
        // ══════════════════════════════════════════════════════════
        $subtotal  = (float) $transaction->total_amount;
        $discount  = (float) ($transaction->discount ?? 0);
        $shipping  = (float) ($transaction->shipping_cost ?? 0);
        $grandTotal = $subtotal - $discount + $shipping;

        $totalsStartCol = self::MAX_COLS - 35;
        $out .= $this->rightAlignedLine("Subtotal (Rp)", number_format($subtotal, 0, ',', '.'), $totalsStartCol);

        if ($shipping > 0) {
            $out .= $this->rightAlignedLine("Ongkos Kirim (+)", number_format($shipping, 0, ',', '.'), $totalsStartCol);
        }
        if ($discount > 0) {
            $out .= $this->rightAlignedLine("Diskon (-)", number_format($discount, 0, ',', '.'), $totalsStartCol);
        }

        $out .= str_pad("", $totalsStartCol) . str_repeat("=", self::MAX_COLS - $totalsStartCol) . self::CR . self::LF;

        // Grand Total (bold)
        $out .= self::ESC . "E";
        $out .= $this->rightAlignedLine("GRAND TOTAL (Rp)", number_format($grandTotal, 0, ',', '.'), $totalsStartCol);
        $out .= self::ESC . "F";

        // DP / Sisa Tagihan
        if ($transaction->payment_status === 'dp') {
            $dp = (float) $transaction->down_payment;
            $sisa = $grandTotal - $dp;
            $out .= $this->rightAlignedLine("Uang Muka / DP (-)", number_format($dp, 0, ',', '.'), $totalsStartCol);
            $out .= str_pad("", $totalsStartCol) . str_repeat("-", self::MAX_COLS - $totalsStartCol) . self::CR . self::LF;
            $out .= self::ESC . "E";
            $out .= $this->rightAlignedLine("SISA TAGIHAN (Rp)", number_format($sisa, 0, ',', '.'), $totalsStartCol);
            $out .= self::ESC . "F";
        }

        $out .= self::CR . self::LF;

        // ══════════════════════════════════════════════════════════
        //  TANDA TANGAN
        // ══════════════════════════════════════════════════════════
        $sig1 = $this->centerInWidth("Penerima Barang,", 26);
        $sig2 = $this->centerInWidth("Hormat Kami,", 26);
        $out .= str_pad($sig1, 40) . $sig2 . self::CR . self::LF;

        // Space for signature
        $out .= self::CR . self::LF;
        $out .= self::CR . self::LF;
        $out .= self::CR . self::LF;

        $line1 = $this->centerInWidth("(________________)", 26);
        $line2 = $this->centerInWidth("(________________)", 26);
        $out .= str_pad($line1, 40) . $line2 . self::CR . self::LF;

        $label1 = $this->centerInWidth("Nama & TTD", 26);
        $label2 = $this->centerInWidth("Pengirim / Sales", 26);
        $out .= str_pad($label1, 40) . $label2 . self::CR . self::LF;

        $out .= self::CR . self::LF;

        // ══════════════════════════════════════════════════════════
        //  FOOTER
        // ══════════════════════════════════════════════════════════
        $out .= str_repeat("-", self::MAX_COLS) . self::CR . self::LF;
        $out .= $this->centerText("Terima kasih atas kepercayaannya.", self::MAX_COLS);
        $out .= $this->centerText("Barang yg sudah dibeli tidak dapat ditukar kecuali ada perjanjian.", self::MAX_COLS);
        $out .= $this->centerText("*** Putih(Pembeli) | Merah/Kuning(Arsip) ***", self::MAX_COLS);

        // ── Form Feed (eject paper) ──
        $out .= self::CR . self::LF;
        $out .= self::FF;

        return $out;
    }

    // ─────────────────────────────────────────────────────────────
    //  Helper Methods
    // ─────────────────────────────────────────────────────────────

    /**
     * Center text within a given width, followed by CR+LF.
     */
    private function centerText(string $text, int $width): string
    {
        $text = mb_substr($text, 0, $width);
        $pad  = max(0, intdiv($width - mb_strlen($text), 2));
        return str_repeat(" ", $pad) . $text . self::CR . self::LF;
    }

    /**
     * Center text within a width (without line break).
     */
    private function centerInWidth(string $text, int $width): string
    {
        $text = mb_substr($text, 0, $width);
        $pad  = max(0, intdiv($width - mb_strlen($text), 2));
        return str_repeat(" ", $pad) . $text;
    }

    /**
     * Two-column layout line.
     */
    private function twoColumnLine(string $left, string $right, int $leftWidth, int $rightWidth): string
    {
        $left  = mb_substr($left, 0, $leftWidth);
        $right = mb_substr($right, 0, $rightWidth);
        return str_pad($left, $leftWidth) . $right . self::CR . self::LF;
    }

    /**
     * Right-aligned label : value line for totals area.
     */
    private function rightAlignedLine(string $label, string $value, int $startCol): string
    {
        $available = self::MAX_COLS - $startCol;
        $valueWidth = 15;
        $labelWidth = $available - $valueWidth - 2; // 2 for ": "

        $line = str_pad("", $startCol);
        $line .= str_pad($label, $labelWidth);
        $line .= ": ";
        $line .= str_pad($value, $valueWidth, " ", STR_PAD_LEFT);
        $line .= self::CR . self::LF;

        return $line;
    }
}
