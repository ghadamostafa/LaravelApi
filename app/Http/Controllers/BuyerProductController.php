<?php

namespace App\Http\Controllers;

use App\Models\buyer;
use Illuminate\Http\Request;

class BuyerProductController extends ApiController
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
    public function index(buyer $buyer)
    {
        $products=$buyer->transactions()->with('product')->get()->pluck('product');
        return $this->showAll($products);
    }

}
