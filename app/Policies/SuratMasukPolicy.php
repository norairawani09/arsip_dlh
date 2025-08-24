<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SuratMasuk;

class SuratMasukPolicy
{
    public function viewAny(User $u): bool { return in_array($u->role, ['admin','user']); }
    public function view(User $u, SuratMasuk $m): bool { return in_array($u->role, ['admin','user']); }

    // BOLEH untuk admin & user
    public function create(User $u): bool { return in_array($u->role, ['admin','user']); }
    public function update(User $u, SuratMasuk $m): bool { return in_array($u->role, ['admin','user']); }
    public function delete(User $u, SuratMasuk $m): bool { return in_array($u->role, ['admin','user']); }
}
