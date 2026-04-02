<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\supplier;
use Illuminate\Auth\Access\HandlesAuthorization;

class supplierPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Supplier');
    }

    public function view(AuthUser $authUser, supplier $supplier): bool
    {
        return $authUser->can('View:Supplier');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Supplier');
    }

    public function update(AuthUser $authUser, supplier $supplier): bool
    {
        return $authUser->can('Update:Supplier');
    }

    public function delete(AuthUser $authUser, supplier $supplier): bool
    {
        return $authUser->can('Delete:Supplier');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Supplier');
    }

    public function restore(AuthUser $authUser, supplier $supplier): bool
    {
        return $authUser->can('Restore:Supplier');
    }

    public function forceDelete(AuthUser $authUser, supplier $supplier): bool
    {
        return $authUser->can('ForceDelete:Supplier');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Supplier');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Supplier');
    }

    public function replicate(AuthUser $authUser, supplier $supplier): bool
    {
        return $authUser->can('Replicate:Supplier');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Supplier');
    }

}