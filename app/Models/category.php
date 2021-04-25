<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\product;
use App\Transformers\CategoryTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;

class category extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable=[
    	'name',
    	'description'
    ];
    protected $hidden=['pivot'];
    public $transformer=CategoryTransformer::class;


    public $timestamps = false;
    public function products()
    {
    	return $this->belongsToMany(product::class);
    }
}
