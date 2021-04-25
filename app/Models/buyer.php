<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\BuyerScope ;
use App\Models\transaction;
use App\Transformers\BuyerTransformer;

class buyer extends User
{
    public $transformer=BuyerTransformer::class;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BuyerScope);
    }

    use HasFactory;
    public function transactions()
    {
    	return $this->hasMany(transaction::class);

    }
}
