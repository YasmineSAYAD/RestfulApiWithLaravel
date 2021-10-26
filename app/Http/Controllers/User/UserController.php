<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\UserCreated;
use App\Http\Controllers\ApiController;
use App\Transformers\UserTransformer;

class UserController extends ApiController
{
    public function __construct(){
      $this->middleware('client.credentials')->only(['store','resend']);
      $this->middleware('auth:api')->except(['store','resend','verify']);
      $this->middleware('transform.input:'. UserTransformer::class)->only(['store','update']);
      $this->middleware('scope:manage-account')->only(['show','update']);
      $this->middleware('can:view,user')->only(['show']);
      $this->middleware('can:update,user')->only(['update']);
      $this->middleware('can:delete,user')->only(['destroy']);
    }


    public function index()
    {
       $this->allowedAdminAction();
       $users=User::all();
       return $this->showAll($users);
       //return response()->json(['data'=>$users],200);//200: response code
    }

    public function store(Request $request)
    {
        $rules=[
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6|confirmed',
        ];
        $this->validate($request,$rules);
        $data=$request->all();
        $data['password']=bcrypt($request->password);
        $data['verified']=User::UNVERIFIED_USER;
        $data['verification_token']=User::generateVerificationCode();
        $data['admin']=User::REGULAR_USER;

        $user=User::create($data);
        return $this->showOne($user);
        //return response()->json(['data'=>$user],201);//200: response code

    }


    public function show(User $user)
    {
       //$user=User::findOrFail($id);
       return $this->showOne($user);
       //return response()->json(['data'=>$user],200);//200: response code
    }


    public function update(Request $request, User $user)
    {
        $this->allowedAdminAction();
        //$user=User::findOrFail($id);
        $rules=[

            'email'=>'required|email|unique:users,email,'.$user->id,
            'password'=>'required|min:6|confirmed',
            'admin'=>'in:'. User::REGULAR_USER . ',' .User::ADMIN_USER,
        ];
       // $this->validate($request,$rules);
        if($request->has('name')){
            $user->name=$request->name;
        }
        if($request->has('email')&& $user->email !=$request->email){
            $user->verified=User::UNVERIFIED_USER;
            $user->verification_token=User::generateVerificationCode();
            $user->email=$request->email;
        }
        if($request->has('password')){
            $user->password=bcrypt($request->name);
        }
        if($request->has('admin')){
            $this->allowedAdminAction();
            if(!$user->isVerified()){

               return $this->errorResponse('Only verified users can modifiy the admin field',409);
              //return response()->json(['error'=>'Only verified users can modifiy the admin field','code'=>409],409);
            }
            $user->admin=$request->admin;
        }
        if(!$user->isDirty()){
            return $this->errorResponse('You need to specify a different value to update',422);
            //return response()->json(['error'=>'You need to specify a different value to update','code'=>422],422);
        }
        $user->save();
        return $this->showOne($user);
        //return response()->json(['data'=>$user],201);//200: response code

    }


    public function destroy(User $user)
    {
        //$user=User::findOrFail($id);
        $user->delete();
        return $this->showOne($user);
        //return response()->json(['data'=>$user],201);//200: response code

    }

    public function me(Request $request){
        $user=$request->user();
        return $this->showOne($user);

    }

    public function verify($token){
      $user=User::where('verification_token',$token)->firstOrFail();
      $user->verified=User::VERIFIED_USER;
      $user->verification_token=null;
      $user->save();
      return $this->showMessage('The account has been verified succesfully');
    }

    public function resend(User $user){
      if($user->isVerified()){
         return $this->errorResponse('This user is already verified',409);
      }
      retry(5,function() use($user){
      Mail::to($user)->send(new UserCreated($user));
      },100);
      return $this->showMessage('The verification email has been resend');
    }
}
