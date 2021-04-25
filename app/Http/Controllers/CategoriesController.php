<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\category;
use App\Transformers\CategoryTransformer;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends ApiController
{
    public function __construct()
    {
        // parent::__construct();
        $this->middleware('transform.input:'.CategoryTransformer::class)->only(['store','update']);
        $this->middleware('auth.api')->except(['index','show']);
        $this->middleware('client.Credentials')->only(['index','show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories=category::all();
        return $this->showAll($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
        ])->validate();
        $category=category::create($request->all());
        return $this->showOne($category,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(category $category)
    {
        return $this->showOne($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,category $category)
    {
        $category->fill($request->only(
            'name','description'
        ));
        if(! $category->isDirty())
        {
            return $this->errorResponse('You must specify any different value to update',422);
        }
        $category->save();
        return $this->showOne($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(category $category)
    {
        $category->delete();
        return $this->showOne($category);
    }
}
