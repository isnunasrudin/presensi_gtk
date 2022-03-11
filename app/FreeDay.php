<?php

namespace App;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreeDay extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'date' => 'date'
    ];

    public static function isFree(Carbon $date) : bool
    {
        return $date->isWeekend() || self::where('date', $date)->exists();
    }

    public static function jumlah_hk($month) : int
    {
        $month = $month ?? Carbon::now();
        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->endOfMonth();

        $periode = CarbonPeriod::create($start, $end)->filter(function(Carbon $date){
            return $date->isWeekday() && FreeDay::where('date', $date->format('Y-m-d'))->doesntExist();
        });

        return $periode->count();
    }
}
