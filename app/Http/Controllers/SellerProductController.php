<?php

namespace App\Http\Controllers;

use App\Models\product;
use App\Models\seller;
use App\Models\User;
use App\Transformers\ProductTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('transform.input:'.ProductTransformer::class)->only(['store','update']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(seller $seller)
    {
        $products=$seller->products;
        return $this->showAll($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,User $seller)
    {
        $rules = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'quantity' =>'required|integer|min:1',
            'image' =>'required|image',

        ])->validate();
        $data=$request->all();
        $data['status']=product::Unavailable_product;
        $data['image']=$request->image->store('');
        $data['seller_id']=$seller->id;
        $product=product::create($data);
        return $this->showOne($product);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,User $seller,product $product)
    {
        $rules = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'quantity' =>'required|integer|min:1',
            'image' =>'image',

        ])->validate();
        $this->checkSeller($seller,$product);
        $product->fill($request->only('name','description','quantity'));
        if($request->has('status'))
        {
            $product->status=$request->status;
            if($product->isAvailable() && $product->categories()->count() == 0)
            {
                $this->errorResponse('An active product must have at least one category',409);
            }
        }
        if($request->hasFile('image'))
        {
            Storage::delete($product->image);
            $product->image=$request->image->store('');
        }
        if($product->isClean())
        {
            $this->errorResponse('You must specify different values',422);
        }
       $product->save();
       return $this->showOne($product);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(seller $seller,product $product)
    {
        $this->checkSeller($seller,$product);
        Storage::delete($product->image);
        $product->delete();
        return $this->showOne($product);
    }
    public function checkSeller($seller,$product)
    {
        if($seller->id != $product->seller_id)
        {
            throw new HttpException(422,'the specified seller is not the seller of the product');
        }
    }
}
