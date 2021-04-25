<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\product;
use App\Scopes\SellerScope;
use App\Transformers\SellerTransformer;

class seller extends User
{
    use HasFactory;
    public $transformer=SellerTransformer::class;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SellerScope);
    }
    
    public function products()
    {
    	return $this->hasMany(product::class);

    }
}
