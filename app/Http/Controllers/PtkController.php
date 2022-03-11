<?php

namespace App\Http\Controllers;

use App\Exports\RekapBulan;
use App\FreeDay;
use App\Presence;
use App\Ptk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PtkController extends Controller
{
    public function index()
    {
        $ptks = Ptk::get();
        $days = $this->day(request()->get('kapan') ?? \Carbon\Carbon::now()->format('Y-m'));
        return view('ptk.index', compact('ptks', 'days'));
    }

    public function libur()
    {
        $freedays = FreeDay::get();
        return view('ptk.libur', compact('freedays'));
    }

    public function libur_post(Request $request)
    {
        FreeDay::create($request->all());
        return response()->json(['status' => true, 'data' => $request->all()]);
    }

    public function addPresence(Request $request)
    {
        Presence::create($request->all());
        return response()->json(['status' => true, 'data' => $request->all()]);
    }

    public function day($date = null)
    {
        $month = request()->get('kapan') ?? \Carbon\Carbon::now()->format('Y-m');
        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->endOfMonth();

        $dates = [];
        while ($start->lte($end)) {
            $dates[] = $start->copy();
            $start->addDay();
        }

        return $dates;

    }

    public function rekap(Request $request)
    {
        return Excel::download(new RekapBulan(request()->get('kapan') ?? \Carbon\Carbon::now()->format('Y-m')), 'a.xlsx');
    }
}
