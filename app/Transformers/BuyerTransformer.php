<?php

namespace App\Transformers;

use App\Models\buyer;
use League\Fractal\TransformerAbstract;

class BuyerTransformer extends TransformerAbstract
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
    public function transform(buyer $buyer)
    {
        return [
            'identifier' => (int) $buyer->id,
            'name' => (string) $buyer->name,
            'email' => (string) $buyer->email,
            'isVerified' => (int) $buyer->verified,
            'createdDate' => (string) $buyer->created_at,
            'changedDate' => (string) $buyer->updated_at,
            'deletedDate' => isset($buyer->deleted_at) ? (string) $buyer->deleted_at : null,
            'links' => [

                [
                    'rel' => 'self',
                    'href' => route('buyers.show', $buyer->id)
                ],

                [
                    'rel' => 'buyers.categories',
                    'href' => route('buyers.categories.index', $buyer->id)
                ],
                [
                    'rel' => 'buyers.sellers',
                    'href' => route('buyers.sellers.index', $buyer->id)
                ],
                [
                    'rel' => 'buyers.products',
                    'href' => route('buyers.products.index', $buyer->id)
                ],
                [
                    'rel' => 'buyers.transactions',
                    'href' => route('buyers.transactions.index', $buyer->id)
                ],
                [
                    'rel' => 'user',
                    'href' => route('users.show', $buyer->id)
                ],

            ]

        ];
    }

    public static function  originalAttributes($index)
    {
        $collection = [
            'identifier' => 'id',
            'name' => 'name',
            'email' => 'email',
            'isVerified' => 'verified',
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
            'name' => 'name',
            'email' => 'email',
            'verified' => 'isVerified',
            'created_at' => 'createdDate',
            'updated_at' => 'changedDate',
            'deleted_at' => 'deletedDate',
        ];
        return isset($collection[$index]) ? $collection[$index] : null;
    }
}
