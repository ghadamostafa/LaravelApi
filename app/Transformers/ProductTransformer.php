<?php

namespace App\Transformers;

use App\Models\product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
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
    public function transform(product $product)
    {
        return [
            'identifier' =>(int) $product->id,
            'name' =>(string) $product->name,
            'details' =>(string) $product->description,
            'stock' =>(int) $product->quantity,
            'status' =>(string) $product->status,
            'picture' =>url('img/'.$product->image),
            'createdDate' =>(string) $product->created_at,
            'changedDate' =>(string) $product->updated_at,
            'deletedDate' =>isset($product->deleted_at)?(string) $product->deleted_at:null,
            'links'=>[

                [
                    'rel'=>'self',
                    'href'=> route('products.show',$product->id)
                ],
               
                [
                    'rel'=>'products.categories',
                    'href'=> route('products.categories.index',$product->id)
                ],
                [
                    'rel'=>'products.buyers',
                    'href'=> route('products.buyers.index',$product->id)
                ],
                [
                    'rel'=>'products.transactions',
                    'href'=> route('products.transactions.index',$product->id)
                ],
                [
                    'rel'=>'seller',
                    'href'=> route('sellers.show',$product->seller_id)
                ],
            ]

        ];
    }
    public static function  originalAttributes($index)
    {
        $collection=[
                'identifier' =>'id',
                'name' =>'name',
                'details' =>'description',
                'stock' =>'quantity',
                'status' =>'status',
                'picture' =>'image',
                'createdDate' =>'created_at',
                'changedDate' =>'updated_at',
                'deletedDate' =>'deleted_at',
        ];
        return isset($collection[$index])?$collection[$index]:null;
    }

    public static function  transformedAttributes($index)
    {
        $collection=[
                'id'=>'identifier',
                'name'=>'name',
                'description'=>'details',
                'quantity'=>'stock',
                'status'=>'status',
                'image'=>'picture',
                'created_at'=>'createdDate',
                'updated_at'=>'changedDate',
                'deleted_at'=>'deletedDate',
        ];
        return isset($collection[$index])?$collection[$index]:null;
    }
}
