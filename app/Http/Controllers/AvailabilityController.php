<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchAvailabilityRequest;
use App\Models\Branch;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\View\View;

class AvailabilityController extends Controller
{
    /**
     * Display the vehicle availability search page and results.
     */
    public function index(SearchAvailabilityRequest $request): View
    {
        $branches = Branch::orderBy('name')->get();

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $branchId = $request->input('branch_id');

        $vehicles = null;
        $days = 1;

        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
            $days = max(1, $start->diffInDays($end));

            $query = Vehicle::with('branch')
                ->availableBetween($startDate, $endDate);

            if ($branchId) {
                $query->where('branch_id', $branchId);
            }

            $vehicles = $query->orderBy('daily_rate')->paginate(9)->withQueryString();
        }

        return view('availability.index', compact('vehicles', 'branches', 'startDate', 'endDate', 'branchId', 'days'));
    }
}
