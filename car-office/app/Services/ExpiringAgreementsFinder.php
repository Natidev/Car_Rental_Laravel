<?php

namespace App\Services;

use App\Models\OfficeRentAgreement;
use Illuminate\Support\Collection;

class ExpiringAgreementsFinder
{
    /**
     * @return Collection<int, OfficeRentAgreement>
     */
    public function getExpiringSoonAgreements(int $days): Collection
    {
        return OfficeRentAgreement::expiringSoon($days)
            ->with(['branch'])
            ->get();
    }
}

