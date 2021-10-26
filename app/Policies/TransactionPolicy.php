<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\AdminActions;

class TransactionPolicy
{
    use HandlesAuthorization,AdminActions;



     // Determine whether the user can view the model.

    public function view(User $user, Transaction $transaction)
    {
        return $user->id===$transaction->buyer->id || $user->id===$transaction->product->seller->id;
    }


}
