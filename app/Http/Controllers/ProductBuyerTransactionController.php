<?php

namespace App\Http\Controllers;

use App\Models\buyer;
use App\Models\product;
use App\Models\transaction;
use App\Transformers\TransactionTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductBuyerTransactionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('transform.input:'.TransactionTransformer::class)->only(['store','update']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,product $product,buyer $buyer)
    {
        $rules = Validator::make($request->all(), [
            'quantity' => 'required|min:1',
        ])->validate();

        if($product->seller_id == $buyer->id){
            return $this->errorResponse('buyer must be different from the seller',419);
        }
        if(! $product->isAvailable()){
            return  $this->errorResponse('product not avialable',409);
        }
        if($product->quantity < $request->quantity)
        {
            return $this->errorResponse('The product does not have enough units for this transaction',409);
        }
        if(!$buyer->isVerified())
        {
            return $this->errorResponse('The buyer must be a verified user',409);

        }
        if(!$product->seller->isVerified())
        {
            return $this->errorResponse('The seller must be a verified user',409);

        }
       return DB::transaction(function()use ($request,$product,$buyer){
            $product->quantity -= $request->quantity;
            $product->save();
            $transaction=transaction::create(['quantity'=>$request->quantity,
            'product_id'=>$product->id,
            'buyer_id'=>$buyer->id]);
            return $this->showOne($transaction);
        });
        
    }

    
}
