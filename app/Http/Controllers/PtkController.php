<?php

namespace App\Http\Controllers;

use App\Presence;
use App\Ptk;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PtkController extends Controller
{
    public function index()
    {
        $ptks = Ptk::get();
        $days = $this->day();
        return view('ptk.index', compact('ptks', 'days'));
    }

    public function addPresence(Request $request)
    {
        Presence::create($request->all());
        return response()->json(['status' => true, 'data' => $request->all()]);
    }

    public function day($date = null)
    {
        $month = $date ?? '2022-01';
        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->endOfMonth();

        $dates = [];
        while ($start->lte($end)) {
            $dates[] = $start->copy();
            $start->addDay();
        }

        return $dates;

    }
}
