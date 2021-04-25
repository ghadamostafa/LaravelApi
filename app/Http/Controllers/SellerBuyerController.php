<?php

namespace App\Http\Controllers;

use App\Models\seller;
use Illuminate\Http\Request;

class SellerBuyerController extends ApiController
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
    public function index(seller $seller)
    {
        $buyers=$seller->products()->whereHas('transactions')
        ->with('transactions.buyer')->get()
        ->pluck('transactions')
        ->collapse()
        ->pluck('buyer')
        ->unique('id')->values();
        return $this->showAll($buyers);
    }

}
