<?php

namespace App\Models;
use App\Models\Buyer;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Transformers\TransactionTransformer;

class Transaction extends Model
{
    use HasFactory,SoftDeletes;

    public $transformer=TransactionTransformer::class;
    public $table = "transactions";
    protected $dates=['deleted_at'];
    protected $fillable=[
        'quantity',
        'product_id',
        'buyer_id',
    ];

    public function buyer(){
        return $this->belongsTo(Buyer::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
