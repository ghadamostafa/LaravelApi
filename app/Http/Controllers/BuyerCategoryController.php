<?php

namespace App\Http\Controllers;

use App\Models\buyer;
use Illuminate\Http\Request;

class BuyerCategoryController extends ApiController
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
        $categories=$buyer->transactions()->with('product.categories')->get()
        ->pluck('product.categories')->collapse()->unique('id')->values();
        return $this->showAll($categories);
    }

}
