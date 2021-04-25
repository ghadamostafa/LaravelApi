<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\category;
use App\Models\seller;
use App\Models\transaction;
use App\Transformers\ProductTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;

class product extends Model
{
    use HasFactory,SoftDeletes;
    const Available_product='available';
    const Unavailable_product='unavailable';
    protected $fillable=[
    	'name',
    	'description',
    	'quantity',
    	'status',
    	'image',
    	'seller_id',

    ];
    public $transformer=ProductTransformer::class;
    protected $hidden=['pivot'];
    public function isAvailable()
    {
    	return $this->status == product::Available_product;
    }
    public function categories()
    {
    	return $this->belongsToMany(category::class);
    }
    public function seller()
    {
    	return $this->belongsTo(seller::class);
    }
    public function transactions()
    {
    	return $this->hasMany(transaction::class);
    }
}
