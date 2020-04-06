<?php

namespace Hayrullah\ErrorManagement\Traits;


use Hayrullah\ErrorManagement\Models\Visit;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasVisits
{
    /**
     * @return MorphToMany
     */
    public function visits()
    {
        return $this->morphMany(Visit::class, 'visitable');
    }

    public function createVisit($request)
    {
        $visit = Visit::createVisit($request);
        $this->visits()->save($visit);
    }
}
