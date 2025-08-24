<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SuratKeluar;

class SuratKeluarPolicy
{
    public function viewAny(User $u): bool { return in_array($u->role, ['admin','user']); }
    public function view(User $u, SuratKeluar $m): bool { return in_array($u->role, ['admin','user']); }

    public function create(User $u): bool { return in_array($u->role, ['admin','user']); }
    public function update(User $u, SuratKeluar $m): bool { return in_array($u->role, ['admin','user']); }
    public function delete(User $u, SuratKeluar $m): bool { return in_array($u->role, ['admin','user']); }
}
