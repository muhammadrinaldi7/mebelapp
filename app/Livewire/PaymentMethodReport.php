<?php

namespace App\Livewire;

use App\Models\PaymentMethod;
use App\Models\TransactionPayment;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PaymentMethodReport extends Component
{
    // 1. Jadikan property public agar bisa di-bind dengan form tanggal di view (wire:model)
    public $startDate;
    public $endDate;
    public $transactions = null;
    public $showTransactions = false;
    public function mount()
    {
        // Set default value saat komponen pertama kali dimuat
        // Tips: Anda bisa menggunakan date('Y-m-01') untuk awal bulan berjalan 
        // dan date('Y-m-t') untuk akhir bulan berjalan.
        $this->startDate = date('Y-m-01');
        $this->endDate = date('Y-m-t');
    }

    public function viewTransaction($idPaymentMethod, $startDate, $endDate)
    {
        $this->showTransactions = true;
        $this->transactions = TransactionPayment::with('transaction')->where('payment_method_id', $idPaymentMethod)
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->get();
        // dd($this->transactions);
    }

    public function render()
    {
        // 2. Tampung property ke variabel lokal agar mudah di-use() oleh closure Eloquent
        $start = $this->startDate;
        $end = $this->endDate;

        $reportPayments = PaymentMethod::where('is_active', 1)
            ->withCount(['transactionPayments' => function ($query) use ($start, $end) {
                $query->whereBetween('payment_date', [$start, $end]);
            }])
            ->withSum(['transactionPayments' => function ($query) use ($start, $end) {
                $query->whereBetween('payment_date', [$start, $end]);
            }], 'amount')
            ->orderByDesc('transaction_payments_sum_amount')
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($item) {
                $item->total_mutasi_masuk = $item->transaction_payments_sum_amount ?? 0;
                $item->jumlah_transaksi = $item->transaction_payments_count;
                return $item;
            });

        // 3. Passing variabel data ke view blade
        return view('livewire.payment-method-report', [
            'reportPayments' => $reportPayments
        ]);
    }
}
