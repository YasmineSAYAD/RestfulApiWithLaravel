<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryBuyerController extends ApiController
{
    public function __construct(){
        parent::__construct();
      }

    public function index(Category $category)
    {
        $this->allowedAdminAction();
        $buyers=$category->products()
        ->whereHas('transactions')
        ->with('transactions.buyer')
        ->get()
        ->pluck('transactions')
        ->collapse()
        ->pluck('buyer')
        ->unique('id')
        ->values();
        return $this->showAll($buyers);

    }


}
