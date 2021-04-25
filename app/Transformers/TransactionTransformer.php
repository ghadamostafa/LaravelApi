<?php

namespace App\Transformers;

use App\Models\transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(transaction $transaction)
    {
        return [
            'identifier' => (int) $transaction->id,
            'quantity' => (int) $transaction->quantity,
            'product' => (int) $transaction->product_id,
            'buyer' => (int) $transaction->buyer_id,
            'createdDate' => (string) $transaction->created_at,
            'changedDate' => (string) $transaction->updated_at,
            'deletedDate' => isset($transaction->deleted_at) ? (string) $transaction->deleted_at : null,
            'links' => [

                [
                    'rel' => 'self',
                    'href' => route('transactions.show', $transaction->id)
                ],

                [
                    'rel' => 'transactions.categories',
                    'href' => route('transactions.categories.index', $transaction->id)
                ],
                [
                    'rel' => 'transactions.sellers',
                    'href' => route('transactions.sellers.index', $transaction->id)
                ],
                [
                    'rel' => 'product',
                    'href' => route('products.show', $transaction->product_id)
                ],
                [
                    'rel' => 'buyer',
                    'href' => route('buyers.show', $transaction->buyer_id)
                ],
            ]
        ];
    }

    public static function  originalAttributes($index)
    {
        $collection = [
            'identifier' => 'id',
            'quantity' => 'quantity',
            'product' => 'product_id',
            'buyer' => 'buyer_id',
            'createdDate' => 'created_at',
            'changedDate' => 'updated_at',
            'deletedDate' => 'deleted_at',
        ];
        return isset($collection[$index]) ? $collection[$index] : null;
    }

    public static function  transformedAttributes($index)
    {
        $collection = [
            'id' => 'identifier',
            'quantity' => 'quantity',
            'product_id' => 'product',
            'buyer_id' => 'buyer',
            'created_at' => 'createdDate',
            'updated_at' => 'changedDate',
            'deleted_at' => 'deletedDate',
        ];
        return isset($collection[$index]) ? $collection[$index] : null;
    }
}
