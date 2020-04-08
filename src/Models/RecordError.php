<?php

namespace Hayrullah\Lem\Models;

use Hayrullah\Lem\Traits\GlobalFunctions;
use Hayrullah\Lem\Traits\HasVisits;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecordError extends Model
{
    use GlobalFunctions;
    use SoftDeletes;
    use HasVisits;

    protected $bPrefix = 'lem/records';

    /**
     * @param $query
     * @param $code
     *
     * @return mixed
     */
    public function scopeType($query, $code)
    {
        return $query->where('code', $code);
    }

    /**
     * @param int $code
     *
     * @return string
     */
    public function showVisits($code = 404)
    {
        $visits = count($this->type($code)->visits);

        return displayVisitsCount($visits);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($row) {
            $row->deleted_by = auth()->id();
            $row->timestamps = false;
            $row->save();
            if ($row->isForceDeleting()) {
                // Here Write What you want make on delete
            }
        });
        static::restoring(function ($row) {
            $row->timestamps = false;
        });
        static::saved(function ($row) {
        });
    }
}
