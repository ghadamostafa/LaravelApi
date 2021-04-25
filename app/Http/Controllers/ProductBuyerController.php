<?php

namespace App\Http\Controllers;

use App\Models\product;
use Illuminate\Http\Request;

class ProductBuyerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(product $product)
    {
        $buyers=$product->transactions()
        ->with('buyer')
        ->get()
        ->pluck('buyer')
        ->unique('id')
        ->values();
        return $this->showAll($buyers);
    }

}
