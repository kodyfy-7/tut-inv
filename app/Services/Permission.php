<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class Permission
{
    function userHasPermission(User $user, string $permissionName): bool
    {
        $permissions = $user->role->permissions;
        return $permissions->contains('name', $permissionName);
    }
}