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
     * Handle the TransactionDetail "updating" event.
     * Adjust product stock based on quantity difference and/or product change.
     */
    public function updating(TransactionDetail $detail): void
    {
        $transaction = $detail->transaction;
        if (!$transaction) {
            return;
        }

        $oldProductId = $detail->getOriginal('product_id');
        $newProductId = $detail->product_id;
        $oldQty = $detail->getOriginal('quantity');
        $newQty = $detail->quantity;

        if ($oldProductId != $newProductId) {
            // Product changed — reverse stock for old product, apply for new product
            $oldProduct = \App\Models\Product::find($oldProductId);
            $newProduct = \App\Models\Product::find($newProductId);

            if ($oldProduct) {
                match ($transaction->type) {
                    'in' => $oldProduct->decrement('current_stock', $oldQty),
                    'out', 'sale' => $oldProduct->increment('current_stock', $oldQty),
                };
            }

            if ($newProduct) {
                match ($transaction->type) {
                    'in' => $newProduct->increment('current_stock', $newQty),
                    'out', 'sale' => $newProduct->decrement('current_stock', $newQty),
                };
            }
        } else {
            // Same product — just adjust by quantity difference
            $product = $detail->product;
            if (!$product) {
                return;
            }

            $diff = $newQty - $oldQty;
            if ($diff === 0) {
                return;
            }

            match ($transaction->type) {
                // Barang Masuk: qty naik → stok naik, qty turun → stok turun
                'in' => $diff > 0
                    ? $product->increment('current_stock', $diff)
                    : $product->decrement('current_stock', abs($diff)),
                // Barang Keluar/Sale: qty naik → stok turun, qty turun → stok naik
                'out', 'sale' => $diff > 0
                    ? $product->decrement('current_stock', $diff)
                    : $product->increment('current_stock', abs($diff)),
            };
        }
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
