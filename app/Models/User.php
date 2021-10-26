<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Transformers\UserTransformer;

class User extends Authenticatable
{
    use HasFactory,Notifiable,SoftDeletes,HasApiTokens;

    const VERIFIED_USER="1";
    const UNVERIFIED_USER="0";
    const ADMIN_USER="true";
    const REGULAR_USER="false";
    public $transformer=UserTransformer::class;
    protected $table='users';
    protected $dates=['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verification_token',
        'admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];
    //Name
    public function setNameAttribute($name){
        $this->attributes['name']=$name;
    }

    public function getNameAttribute($name){
       return ucwords($name);
    }

    //Email
    public function setEmailAttribute($email){
        $this->attributes['email']=strtolower($email);
    }

    public function isVerified(){
      return $this->verified==User::VERIFIED_USER;
    }

    public function isAdmin(){
        return $this->admin==User::ADMIN_USER;
    }

    public static function generateVerificationCode(){
        return Str::random(40);
    }


}
