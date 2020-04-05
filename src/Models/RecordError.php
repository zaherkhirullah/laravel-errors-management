<?php

namespace App;

use App\Traits\GlobalFunctions;
use App\Traits\HasVisits;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecordError extends Model
{
    use GlobalFunctions, SoftDeletes;
    use HasVisits;

    protected $bPrefix = "admin/error-records";

    /**
     * @param $query
     * @param $code
     * @return mixed
     */
    public function scopeType($query, $code)
    {
        return $query->where('code', $code);
    }

    /**
     * @return string
     */
    public static function showVisits($code=404)
    {
        return displayVisitsCount(count(self::Type($code)->visits()));
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
