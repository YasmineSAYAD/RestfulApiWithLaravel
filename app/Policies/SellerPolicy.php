<?php

namespace App\Policies;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\AdminActions;

class SellerPolicy
{
    use HandlesAuthorization, AdminActions;



     // Determine whether the user can view the model.

    public function view(User $user, Seller $seller)
    {
        return $user->id===$seller->id;
    }


     // Determine whether the user can sale something.

    public function sale(User $user, Seller $seller)
    {
        return $user->id===$seller->id;
    }


     // Determine whether the user can update a product.

    public function editProduct(User $user, Seller $seller)
    {
        return $user->id===$seller->id;
    }


     // Determine whether the user can delete a product.

    public function delete(User $user, Seller $seller)
    {
        return $user->id===$seller->id;
    }




}
