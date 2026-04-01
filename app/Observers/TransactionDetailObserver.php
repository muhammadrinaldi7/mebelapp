<?php

namespace App\Observers;

use App\Models\TransactionDetail;

class TransactionDetailObserver
{
    /**
     * Handle the TransactionDetail "created" event.
     * Automatically update product stock based on transaction type.
     */
    public function created(TransactionDetail $detail): void
    {
        $transaction = $detail->transaction;
        $product = $detail->product;

        if (!$transaction || !$product) {
            return;
        }

        match ($transaction->type) {
            'in' => $product->increment('current_stock', $detail->quantity),
            'out', 'sale' => $product->decrement('current_stock', $detail->quantity),
        };
    }

    /**
     * Handle the TransactionDetail "deleted" event.
     * Reverse the stock change when a detail is deleted.
     */
    public function deleted(TransactionDetail $detail): void
    {
        $transaction = $detail->transaction;
        $product = $detail->product;

        if (!$transaction || !$product) {
            return;
        }

        // Reverse the stock adjustment
        match ($transaction->type) {
            'in' => $product->decrement('current_stock', $detail->quantity),
            'out', 'sale' => $product->increment('current_stock', $detail->quantity),
        };
    }
}
