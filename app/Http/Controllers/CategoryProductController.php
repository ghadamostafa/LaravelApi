<?php

namespace App\Http\Controllers;

use App\Models\category;
use Illuminate\Http\Request;

class CategoryProductController extends ApiController
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
    public function index(category $category)
    {
        $products=$category->products;
        return $this->showAll($products);
    }

}
