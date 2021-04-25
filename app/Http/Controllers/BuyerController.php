<?php

namespace App\Http\Controllers;

use App\Models\buyer;
use App\Models\User;

use Illuminate\Http\Request;


class BuyerController extends ApiController
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
    public function index()
    {
        $buyers=buyer::all();
        return $this->showAll($buyers);
    }

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(buyer $buyer)
    {
        return $this->showOne($buyer);
    }

}
