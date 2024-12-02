<?php

namespace App\Policies;

use App\Models\{Opportunity, User};

class OpportunityPolicy
{
    public function update(User $user, Opportunity $opportunity): bool
    {
        return $user->is($opportunity->user) && !$opportunity->isPending();
    }
    public function delete(User $user, Opportunity $opportunity): bool
    {
        return $user->is($opportunity->user);
    }
}
