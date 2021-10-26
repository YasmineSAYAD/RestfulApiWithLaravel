<?php

namespace App\Models;
use App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Transformers\CategoryTransformer;

class Category extends Model
{
    use HasFactory,SoftDeletes;

    public $transformer=CategoryTransformer::class;
    public $table = "categories";
    protected $dates=['deleted_at'];
    protected $fillable=[
        'name',
        'description',
    ];
    protected $hidden=[
        'pivot'
    ];
    public function products(){
        return $this->BelongsToMany(Product::class);
    }
}
