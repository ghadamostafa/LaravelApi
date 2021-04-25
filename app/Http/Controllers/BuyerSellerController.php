<?php

namespace App\Http\Controllers;

use App\Models\buyer;
use Illuminate\Http\Request;

class BuyerSellerController extends ApiController
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
        $sellers=$buyer->transactions()->with('product.seller')->get()
        ->pluck('product.seller')
        ->unique('id')
        ->values();
        return $this->showAll($sellers);
    }

}
