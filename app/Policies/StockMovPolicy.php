<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\StockMov;
use Illuminate\Auth\Access\HandlesAuthorization;

class StockMovPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:StockMov');
    }

    public function view(AuthUser $authUser, StockMov $stockMov): bool
    {
        return $authUser->can('View:StockMov');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:StockMov');
    }

    public function update(AuthUser $authUser, StockMov $stockMov): bool
    {
        return $authUser->can('Update:StockMov');
    }

    public function delete(AuthUser $authUser, StockMov $stockMov): bool
    {
        return $authUser->can('Delete:StockMov');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:StockMov');
    }

    public function restore(AuthUser $authUser, StockMov $stockMov): bool
    {
        return $authUser->can('Restore:StockMov');
    }

    public function forceDelete(AuthUser $authUser, StockMov $stockMov): bool
    {
        return $authUser->can('ForceDelete:StockMov');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:StockMov');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:StockMov');
    }

    public function replicate(AuthUser $authUser, StockMov $stockMov): bool
    {
        return $authUser->can('Replicate:StockMov');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:StockMov');
    }

}