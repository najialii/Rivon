<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Price;
use Illuminate\Auth\Access\HandlesAuthorization;

class PricePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Price');
    }

    public function view(AuthUser $authUser, Price $price): bool
    {
        return $authUser->can('View:Price');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Price');
    }

    public function update(AuthUser $authUser, Price $price): bool
    {
        return $authUser->can('Update:Price');
    }

    public function delete(AuthUser $authUser, Price $price): bool
    {
        return $authUser->can('Delete:Price');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Price');
    }

    public function restore(AuthUser $authUser, Price $price): bool
    {
        return $authUser->can('Restore:Price');
    }

    public function forceDelete(AuthUser $authUser, Price $price): bool
    {
        return $authUser->can('ForceDelete:Price');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Price');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Price');
    }

    public function replicate(AuthUser $authUser, Price $price): bool
    {
        return $authUser->can('Replicate:Price');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Price');
    }

}