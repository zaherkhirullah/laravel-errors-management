<?php

namespace Hayrullah\RecordErrors\Traits;


use App\User;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait GlobalFunctions
{
    /**
     * @param $attr
     * @return mixed
     */
    function getAttr($attr)
    {
        return $this->{$attr.'_'.app()->getLocale()};
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured_'.app()->getLocale(), '=', 1);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeHasTranslate($query)
    {
        $title = 'title_'.app()->getLocale();
        return $query->where($title, '!=', '');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where("active", '=', 1);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeNotActive($query)
    {
        return $query->where("active", '=', 0);
    }

    /**
     * @return UrlGenerator|string
     */
    function getFolderPathAttribute()
    {
        return $this->folderName;
    }

    /**
     * @return UrlGenerator|string
     */
    function getImagePathAttribute()
    {
        return asset("$this->folderName/$this->image");
//        return "https://arkanproje.com/$this->folderName/$this->image";
    }

    /**
     * @return UrlGenerator|string
     */
    function getImagePlaceholderPathAttribute()
    {
        $image = asset('assets/img/lazy.jpg');
        if ($this->image) {
            $file = "$this->folderName/$this->image";
            if (file_exists(public_path($file)) and !is_dir($file)) {
//                return "https://arkanproje.com/$file";
                $image = asset($file);
            }
        }
        return $image;
    }


    /**
     * @return UrlGenerator|string
     */
    function getBPrefixPathAttribute()
    {
        return url($this->bPrefix);
    }

    /**
     * @return UrlGenerator|string
     */
    function getFPrefixPathAttribute()
    {
        return url($this->fPrefix);
    }

    /**
     * @return BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return BelongsTo
     */
    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }

    /**
     * @return BelongsTo
     */
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * @return BelongsTo
     */
    public function restoredBy()
    {
        return $this->belongsTo(User::class, 'restored_by');
    }

}
