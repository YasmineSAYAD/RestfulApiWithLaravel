<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\User;
class UserTransformer extends TransformerAbstract
{

    public function transform(User $user)
    {
        return [
            'identifier'=>(int)$user->id,
            'name'=>(string)$user->name,
            'email'=>(string)$user->email,
            'isVerified'=>(int)$user->verified,
            'isAdmin'=>($user->admin==='true'),
            'creationDate'=>(string)$user->created_at,
            'lastChange'=>(string)$user->updated_at,
            'deleteDate'=>isset($user->deleted_at) ? (string) $user->deleted_at : null,
            'links'=>[
                [
                    'rel'=>'self',
                    'href'=>route('users.show',$user->id),
                ],
              ]
        ];
    }

    public static function originalAttribute($index){
        $attributes= [
            'identifier'=>'id',
            'name'=>'name',
            'email'=>'email',
            'isVerified'=>'verified',
            'isAdmin'=>'admin',
            'creationDate'=>'created_at',
            'lastChange'=>'updated_at',
            'deleteDate'=>'deleted_at',
        ];
      return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformedAttribute($index){
        $attributes= [
            'id'=>'identifier',
            'name'=>'name',
            'email'=>'email',
            'verified'=>'isVerified',
            'admin'=>'isAdmin',
            'created_at'=>'creationDate',
            'updated_at'=>'lastChange',
            'deleted_at'=>'deleteDate',
        ];
      return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
