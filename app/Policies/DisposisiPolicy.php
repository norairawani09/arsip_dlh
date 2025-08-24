<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Disposisi;

class DisposisiPolicy
{
    public function viewAny(User $u): bool { return in_array($u->role, ['admin','user']); }
    public function view(User $u, Disposisi $m): bool { return in_array($u->role, ['admin','user']); }

    public function create(User $u): bool { return in_array($u->role, ['admin','user']); }
    public function update(User $u, Disposisi $m): bool { return in_array($u->role, ['admin','user']); }
    public function delete(User $u, Disposisi $m): bool { return in_array($u->role, ['admin','user']); }
}
