<?php

namespace Hayrullah\ErrorManagement\Traits;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait LockPage
{
    /**
     * @return bool
     */
    public function refresh_locked()
    {
        //  dd($this->locked_at,$this->locked_by);
        $this->locked_at = Carbon::now()->format('Y-m-d H:i:s');
        $this->locked_by = auth()->id();
        $this->timestamps = false;
        $this->save();
        return true;
    }

    /**
     * @return bool
     */
    function is_locked()
    {
        //  dd($this->locked_at,$this->locked_by);
        if($this->locked_at == null){
            return false;
        }
        if($this->locked_by == auth()->id()){
            return false;
        }
        $now = Carbon::now();
        $locked_to = Carbon::parse($this->locked_at);
        $totalDuration = $now->diffInSeconds($locked_to);
        if($totalDuration > 10){
            return false;
        }
        return true;
    }

    /**
     * @return BelongsTo
     */
    public function lockedBy()
    {
        return $this->belongsTo(User::class, 'locked_by');
    }


}
