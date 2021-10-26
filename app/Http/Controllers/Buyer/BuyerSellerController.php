<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerSellerController extends ApiController
{
    public function __construct(){
        parent::__construct();
      }
    public function index(Buyer $buyer)
    {
      $this->allowedAdminAction();
      $sellers=$buyer->transactions()
      ->with('product.seller')
      ->get()
      ->pluck('product.seller')
      ->unique('id')//list not repeated
      ->values();//list not empty
      return $this->showAll($sellers);
    }


}
