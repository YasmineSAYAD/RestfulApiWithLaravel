<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buyer;
use App\Http\Controllers\ApiController;

class BuyerController extends ApiController
{
    public function __construct(){
        parent::__construct();
        $this->middleware('scope:read-general')->only(['index']);
        $this->middleware('can:view,buyer')->only(['show']);
      }

    public function index()
    {
        $this->allowedAdminAction();
        $buyers=Buyer::has('transactions')->get();
        return $this->showAll($buyers);

    }


    public function show(Buyer $buyer)
    {
       // $buyer=Buyer::has('transactions')->findOrFail($id);
        return $this->showOne($buyer);
       // return response()->json(['data'=>$buyer],200);//200: response code
    }


}
