<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Company $company): bool
    {
        return $user->role === UserRole::ADMIN ||
            $company->users()->where('user_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function update(User $user, Company $company): bool
    {
        return $this->create($user);
    }

    public function delete(User $user, Company $company): bool
    {
        return $this->create($user);
    }
}
