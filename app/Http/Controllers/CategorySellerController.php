<?php

namespace App\Http\Controllers;

use App\Models\category;
use Illuminate\Http\Request;

class CategorySellerController extends ApiController
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
        $sellers=$category->products()->with('seller')->get()->pluck('seller')->unique('id')->values();
        return $this->showAll($sellers);
    }

  
}
