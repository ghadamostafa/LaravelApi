<?php

namespace App\Transformers;

use App\Models\category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(category $category)
    {
        return [
            'identifier' => (int) $category->id,
            'title' => (string) $category->name,
            'details' => (string) $category->description,
            'createdDate' => (string) $category->created_at,
            'changedDate' => (string) $category->updated_at,
            'deletedDate' => isset($category->deleted_at) ? (string) $category->deleted_at : null,
            'links' => [

                [
                    'rel' => 'self',
                    'href' => route('categories.show', $category->id)
                ],
                [
                    'rel' => 'categories.products',
                    'href' => route('categories.products.index', $category->id)
                ],
                [
                    'rel' => 'categories.sellers',
                    'href' => route('categories.sellers.index', $category->id)
                ],
                [
                    'rel' => 'categories.buyers',
                    'href' => route('categories.buyers.index', $category->id)
                ],
                [
                    'rel' => 'categories.transactions',
                    'href' => route('categories.transactions.index', $category->id)
                ],
            ]

        ];
    }

    public static function  originalAttributes($index)
    {
        $collection = [
            'identifier' => 'id',
            'title' => 'name',
            'details' => 'description',
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
            'name' => 'title',
            'description' => 'details',
            'created_at' => 'createdDate',
            'updated_at' => 'changedDate',
            'deleted_at' => 'deletedDate',
        ];
        return isset($collection[$index]) ? $collection[$index] : null;
    }
}
