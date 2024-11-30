<?php

namespace App\Models;

use App\Scopes\BusinessIdScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Warranty extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','business_id','duration','duration_type'];

    protected static function booted()
    {
        static::addGlobalScope(new BusinessIdScope);
       
    }

    public static function forDropdown($business_id)
    {
        $warranties = Warranty::where('business_id', $business_id)
                            ->get();
        $dropdown = [];

        foreach ($warranties as $warranty) {
            $dropdown[$warranty->id] = $warranty->name . ' (' . $warranty->duration . ' ' . __('lang_v1.' . $warranty->duration_type) . ')';
        }
        
        return $dropdown;
    }

    /**
     * Get the display name.
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        $name = $this->name . ' (' . $this->duration . ' ' . __('lang_v1.' . $this->duration_type) . ')';
        return $name;
    }

    public function getEndDate($date)
    {
        $date_obj = Carbon::parse($date);

        if ($this->duration_type == 'days') {
            $date_obj->addDays($this->duration);
        } elseif ($this->duration_type == 'months') {
            $date_obj->addMonths($this->duration);
        } elseif ($this->duration_type == 'years') {
            $date_obj->addYears($this->duration);
        }

        return $date_obj->toDateTimeString();
    }
}
