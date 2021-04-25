<?php

namespace App\Http\Controllers;

use App\Models\transaction;
use Illuminate\Http\Request;

class TransactionCategoryController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.Credentials')->only(['index']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(transaction $transaction)
    {
       $categories= $transaction->product->categories;
       return $this->showAll($categories);
    }

    
}
