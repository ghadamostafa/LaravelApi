<?php

namespace App\Transformers;

use App\Models\seller;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
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
    public function transform(seller $seller)
    {
        return [
            'identifier' => (int) $seller->id,
            'name' => (string) $seller->name,
            'email' => (string) $seller->email,
            'isVerified' => (int) $seller->verified,
            'createdDate' => (string) $seller->created_at,
            'changedDate' => (string) $seller->updated_at,
            'deletedDate' => isset($seller->deleted_at) ? (string) $seller->deleted_at : null,
            'links' => [

                [
                    'rel' => 'self',
                    'href' => route('sellers.show', $seller->id)
                ],

                [
                    'rel' => 'sellers.categories',
                    'href' => route('sellers.categories.index', $seller->id)
                ],
                [
                    'rel' => 'sellers.buyers',
                    'href' => route('sellers.buyers.index', $seller->id)
                ],
                [
                    'rel' => 'sellers.products',
                    'href' => route('sellers.products.index', $seller->id)
                ],
                [
                    'rel' => 'sellers.transactions',
                    'href' => route('sellers.transactions.index', $seller->id)
                ],
                [
                    'rel' => 'user',
                    'href' => route('users.show', $seller->id)
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
