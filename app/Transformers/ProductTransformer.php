<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Product;

class ProductTransformer extends TransformerAbstract
{

    public function transform(Product $product)
    {
        return [
            'identifier'=>(int)$product->id,
            'stock'=>(int)$product->quantity,
            'title'=>(string)$product->name,
            'details'=>(string)$product->description,
            'situation'=>(string)$product->status,
            'picture'=>url("images/{$product->image}"),
            'seller'=>(int)$product->seller_id,
            'creationDate'=>(string)$product->created_at,
            'lastChange'=>(string)$product->updated_at,
            'deleteDate'=>isset($product->deleted_at) ? (string) $product->deleted_at : null,
            'links'=>[
                [
                    'rel'=>'self',
                    'href'=>route('products.show',$product->id),
                ],
                [
                    'rel'=>'product.buyers',
                    'href'=>route('products.buyers.index',$product->id),
                ],
                [
                    'rel'=>'product.categories',
                    'href'=>route('products.categories.index',$product->id),
                ],

                [
                    'rel'=>'product.transactions',
                    'href'=>route('products.transactions.index',$product->id),
                ],
                [
                    'rel'=>'seller',
                    'href'=>route('sellers.show',$product->seller_id),
                ],
            ]
        ];
    }


    public static function originalAttribute($index){
        $attributes= [
            'identifier'=>'id',
            'stock'=>'quantity',
            'title'=>'name',
            'details'=>'description',
            'situation'=>'status',
            'picture'=>'image',
            'seller'=>'seller_id',
            'creationDate'=>'created_at',
            'lastChange'=>'updated_at',
            'deleteDate'=>'deleted_at',
        ];
      return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformedAttribute($index){
        $attributes= [
            'id'=>'identifier',
            'quantity'=>'stock',
            'name'=>'title',
            'description'=>'details',
            'status'=>'situation',
            'image'=>'picture',
            'seller_id'=>'seller',
            'created_at'=>'creationDate',
            'updated_at'=>'lastChange',
            'deleted_at'=>'deleteDate',
        ];
      return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
