<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Services\FleetStatistics;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StatisticsController extends Controller
{
    public function __construct(
        protected FleetStatistics $fleetStatistics
    ) {}

    /**
     * Display fleet statistics overview and breakdown.
     */
    public function index(Request $request): View
    {
        $branches = Branch::orderBy('name')->get();

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $branchId = $request->input('branch_id');

        // Tarih doğrulaması
        $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'branch_id' => ['nullable', 'exists:branches,id'],
        ], [
            'end_date.after_or_equal' => 'Bitiş tarihi başlangıç tarihinden önce olamaz.',
            'branch_id.exists' => 'Seçilen şube sistemde bulunamadı.',
        ]);

        $stats = $this->fleetStatistics->getStatistics(
            $startDate,
            $endDate,
            $branchId ? (int) $branchId : null
        );

        return view('statistics.index', compact('stats', 'branches', 'startDate', 'endDate', 'branchId'));
    }
}
