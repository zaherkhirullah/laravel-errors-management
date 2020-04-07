<?php

namespace Hayrullah\ErrorsManagement\Models;

use Hayrullah\ErrorsManagement\Traits\GlobalFunctions;
use Hayrullah\ErrorsManagement\Traits\HasVisits;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecordError extends Model
{
    use GlobalFunctions;
    use SoftDeletes;
    use HasVisits;

    protected $bPrefix = 'errors-management/records';

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
    public static function showVisits($code = 404)
    {
        $typeVisits = self::Type($code)->visits();
        $visits = count($typeVisits);

        return displayVisitsCount($visits);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        static::deleting(function($row) {
            $row->deleted_by = auth()->id();
            $row->timestamps = false;
            $row->save();
            if ($row->isForceDeleting()) {
                // Here Write What you want make on delete
            }
        });
        static::restoring(function($row) {
            $row->timestamps = false;
        });
        static::saved(function($row) {
        });
    }
}
