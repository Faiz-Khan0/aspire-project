<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\CheckIn;
class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $week = $request->input('week', now()->startOfWeek()->format('Y-m-d'));

        $start = Carbon::parse($week)->startOfWeek();
        $end = Carbon::parse($week)->endOfWeek();

        $checkinData = CheckIn::with('service', 'subservice')
            ->whereBetween('created_at', [$start, $end])
            ->get()
            ->groupBy(fn ($checkin) => $checkin->service->name)
            ->map(function ($serviceGroup) {
                return $serviceGroup->groupBy(fn ($c) => $c->subservice->name)->map->count();
            });

        return view('dashboard', [
            'checkinData' => $checkinData,
            'selectedWeek' => $start->toDateString(),
        ]);
    }
}

