<?php

namespace App\Policies;

use App\Manifest;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ManifestPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function create(User $user)
    {
        return true;
    }

    public function cancel(User $user, Manifest $manifest)
    {
        return $user->id === $manifest->created_by;
    }
}
