<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Jentry;
use Illuminate\Auth\Access\HandlesAuthorization;

class JentryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Jentry');
    }

    public function view(AuthUser $authUser, Jentry $jentry): bool
    {
        return $authUser->can('View:Jentry');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Jentry');
    }

    public function update(AuthUser $authUser, Jentry $jentry): bool
    {
        return $authUser->can('Update:Jentry');
    }

    public function delete(AuthUser $authUser, Jentry $jentry): bool
    {
        return $authUser->can('Delete:Jentry');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Jentry');
    }

    public function restore(AuthUser $authUser, Jentry $jentry): bool
    {
        return $authUser->can('Restore:Jentry');
    }

    public function forceDelete(AuthUser $authUser, Jentry $jentry): bool
    {
        return $authUser->can('ForceDelete:Jentry');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Jentry');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Jentry');
    }

    public function replicate(AuthUser $authUser, Jentry $jentry): bool
    {
        return $authUser->can('Replicate:Jentry');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Jentry');
    }

}