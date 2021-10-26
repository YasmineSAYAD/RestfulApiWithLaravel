<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\AdminActions;
class UserPolicy
{
    use HandlesAuthorization,AdminActions;



     // Determine whether the user can view the model.

    public function view(User $authentificatedUser, User $user)
    {
        return $authentificatedUser->id===$user->id;
    }



     // Determine whether the user can update the model.

    public function update(User $authentificatedUser, User $user)
    {
        return $authentificatedUser->id===$user->id;
    }


     // Determine whether the user can delete the model.

    public function delete(User $authentificatedUser, User $user)
    {
        return $authentificatedUser->id===$user->id &&
               $authentificatedUser->token()->client->personal_access_client;
    }


}
