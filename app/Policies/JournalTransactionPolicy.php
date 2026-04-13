<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\JournalTransaction;
use Illuminate\Auth\Access\HandlesAuthorization;

class JournalTransactionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:JournalTransaction');
    }

    public function view(AuthUser $authUser, JournalTransaction $journalTransaction): bool
    {
        return $authUser->can('View:JournalTransaction');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:JournalTransaction');
    }

    public function update(AuthUser $authUser, JournalTransaction $journalTransaction): bool
    {
        return $authUser->can('Update:JournalTransaction');
    }

    public function delete(AuthUser $authUser, JournalTransaction $journalTransaction): bool
    {
        return $authUser->can('Delete:JournalTransaction');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:JournalTransaction');
    }

    public function restore(AuthUser $authUser, JournalTransaction $journalTransaction): bool
    {
        return $authUser->can('Restore:JournalTransaction');
    }

    public function forceDelete(AuthUser $authUser, JournalTransaction $journalTransaction): bool
    {
        return $authUser->can('ForceDelete:JournalTransaction');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:JournalTransaction');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:JournalTransaction');
    }

    public function replicate(AuthUser $authUser, JournalTransaction $journalTransaction): bool
    {
        return $authUser->can('Replicate:JournalTransaction');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:JournalTransaction');
    }

}