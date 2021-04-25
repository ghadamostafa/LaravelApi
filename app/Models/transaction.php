<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\buyer;
use App\Models\product;
use App\Transformers\TransactionTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;

class transaction extends Model
{
    use HasFactory,SoftDeletes;
     protected $fillable=[
    	'quantity',
    	'buyer_id',
    	'product_id'
     ];
    public $transformer=TransactionTransformer::class;
     
    public function buyer()
    {
    	return $this->belongsTo(buyer::class);
    }
    public function product()
    {
    	return $this->belongsTo(product::class);
    }
}
