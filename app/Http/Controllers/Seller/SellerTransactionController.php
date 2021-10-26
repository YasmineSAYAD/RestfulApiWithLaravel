<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerTransactionController extends ApiController
{
    public function __construct(){
        parent::__construct();
        $this->middleware('scope:read-general')->only(['index']);
        $this->middleware('can:view,seller')->only(['index']);
      }

    public function index(Seller $seller)
    {
       $transactions=$seller->products()
       ->whereHas('transactions')
       ->with('transactions')
       ->get()
       ->pluck('transactions')
       ->collapse();

       return $this->showAll($transactions);
    }

}
