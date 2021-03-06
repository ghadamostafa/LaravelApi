<?php

namespace App\Http\Controllers;

use App\Models\category;
use Illuminate\Http\Request;

class CategoryTransactionController extends ApiController
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
    public function index(category $category)
    {
        $transactions=$category->products()->whereHas('transactions')->with('transactions')->get()
        ->pluck('transactions')
        ->collapse();
        return $this->showAll($transactions);
    }

  
}
