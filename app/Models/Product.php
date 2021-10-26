<?php

namespace App\Models;
use App\Models\Category;
use App\Models\Seller;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Transformers\ProductTransformer;

class Product extends Model
{
    use HasFactory,SoftDeletes;

    public $transformer=ProductTransformer::class;
    public $table = "products";
    protected $dates=['deleted_at'];
    const AVAILABLE_PRODUCT='available';
    const UNAVAILABLE_PRODUCT='unavailable';
    protected $fillable=[
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'seller_id',
    ];
    protected $hidden=[
        'pivot'
    ];
    public function isAvailable(){
      return $this->status==Product::AVAILABLE_PRODUCT;
    }

    public function categories(){
        return $this->BelongsToMany(Category::class);
    }

    public function seller(){
        return $this->BelongsTo(Seller::class);
    }

    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
}
