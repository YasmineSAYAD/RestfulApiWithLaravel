<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use App\Http\Controllers\ApiController;

class SellerController extends ApiController
{
    public function __construct(){
        parent::__construct();
        $this->middleware('scope:read-general')->only(['show']);
        $this->middleware('can:view,seller')->only(['show']);
      }
    public function index()
    {
        $this->allowedAdminAction();
        $sellers=Seller::has('products')->get();
        return $this->showAll($sellers);
       // return response()->json(['data'=>$sellers],200);//200: response code
    }


    public function show(Seller $seller)
    {
        //$seller=Seller::has('products')->findOrFail($id);
        return $this->showOne($seller);
        //return response()->json(['data'=>$seller],200);//200: response code
    }

}
