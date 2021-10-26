<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductCategoryController extends ApiController
{

    public function __construct(){
        $this->middleware('client.credentials')->only(['index']);
        $this->middleware('auth:api')->except(['index']);
        $this->middleware('scope:manage-product')->except(['index']);
        $this->middleware('can:deleteCategory,product')->only(['destroy']);
        $this->middleware('can:addCategory,product')->only(['update']);
      }
    public function index(Product $product)
    {
        $categories=$product->categories;
        return $this->showAll($categories);
    }

    public function update(Request $request, Product $product,Category $category){
        //try attach method, syncWithoutDetaching methodand sync method
        $product->categories()->syncWithoutDetaching([$category->id]);
        return $this->showAll($product->categories);
    }
    public function destroy(Product $product, Category $category){
        if(!$product->categories()->find($category->id)){
            $this->errorResponse('The specified category is not a category of this product',404);
        }
        $product->categories()->detach($category->id);
        return $this->showAll($product->categories);

    }


}
