<?php

namespace App;

use Carbon\Carbon;
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
        $month = $month ?? '2022-01';
        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->endOfMonth();

        $dates = [];
        while ($start->lte($end)) {
            if($start->isWeekday() || self::where('date', $start)->exists()){}
            else{
                $dates[] = $start->copy();
            }
            $start->addDay();
        }

        return $end->daysInMonth - count($dates);
    }
}
