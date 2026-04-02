<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Cost;
use Illuminate\Auth\Access\HandlesAuthorization;

class CostPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Cost');
    }

    public function view(AuthUser $authUser, Cost $cost): bool
    {
        return $authUser->can('View:Cost');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Cost');
    }

    public function update(AuthUser $authUser, Cost $cost): bool
    {
        return $authUser->can('Update:Cost');
    }

    public function delete(AuthUser $authUser, Cost $cost): bool
    {
        return $authUser->can('Delete:Cost');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Cost');
    }

    public function restore(AuthUser $authUser, Cost $cost): bool
    {
        return $authUser->can('Restore:Cost');
    }

    public function forceDelete(AuthUser $authUser, Cost $cost): bool
    {
        return $authUser->can('ForceDelete:Cost');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Cost');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Cost');
    }

    public function replicate(AuthUser $authUser, Cost $cost): bool
    {
        return $authUser->can('Replicate:Cost');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Cost');
    }

}