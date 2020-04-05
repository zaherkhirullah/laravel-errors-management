<?php
/**
 * Created by PhpStorm.
 * User: a.alkari
 * Date: 8/31/2019
 * Time: 1:46 PM
 */

namespace App\Traits;


use App\Visit;
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
