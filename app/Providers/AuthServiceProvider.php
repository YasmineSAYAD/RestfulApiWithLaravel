<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Carbon\Carbon;
use App\Policies\BuyerPolicy;
use App\Policies\SellerPolicy;
use App\Policies\UserPolicy;
use App\Policies\TransactionPolicy;
use App\Policies\ProductPolicy;
use App\Models\Buyer;
use App\Models\Seller;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Product;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         Buyer::class => BuyerPolicy::class,
         Seller::class => SellerPolicy::class,
         User::class => UserPolicy::class,
         Transaction::class => TransactionPolicy::class,
         Product::class => ProductPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {

        $this->registerPolicies();
        Gate::define('admin-action',function($user){
           return $user->isAdmin();
        });
        Passport::routes();
        Passport::tokensExpireIn(Carbon::now()->addMinutes(30));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
        Passport::enableImplicitGrant();
        Passport::Tokenscan([
            'purchase-product'=>'Create a new transaction for a specific product',
            'manage-product'=>'Create, read, update and delete products (CRUD)',
            'manage-account'=>'Read your account data, id, name, email,
                               if verified, and if admin (cannot read password).
                               Modify your account data (email and password).
                               Cannot delete your account',
            'read-general'=>'Read general information like purchasing categoies,
                             purchased products, selling products, selling categories,
                             your transactions (purchases and sales) ',
        ]);
        //
    }
}
