<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Loyalitypt;
use Illuminate\Auth\Access\HandlesAuthorization;

class LoyalityptPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Loyalitypt');
    }

    public function view(AuthUser $authUser, Loyalitypt $loyalitypt): bool
    {
        return $authUser->can('View:Loyalitypt');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Loyalitypt');
    }

    public function update(AuthUser $authUser, Loyalitypt $loyalitypt): bool
    {
        return $authUser->can('Update:Loyalitypt');
    }

    public function delete(AuthUser $authUser, Loyalitypt $loyalitypt): bool
    {
        return $authUser->can('Delete:Loyalitypt');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Loyalitypt');
    }

    public function restore(AuthUser $authUser, Loyalitypt $loyalitypt): bool
    {
        return $authUser->can('Restore:Loyalitypt');
    }

    public function forceDelete(AuthUser $authUser, Loyalitypt $loyalitypt): bool
    {
        return $authUser->can('ForceDelete:Loyalitypt');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Loyalitypt');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Loyalitypt');
    }

    public function replicate(AuthUser $authUser, Loyalitypt $loyalitypt): bool
    {
        return $authUser->can('Replicate:Loyalitypt');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Loyalitypt');
    }

}