<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Report;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Barryvdh\DomPDF\Facade\PDF;

class ReportController extends Controller
{
    public function generateMonthlyReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $monthlyReport = Transaction::with(['payments', 'user'])
            ->whereBetween('due_on', [$startDate, $endDate])
            ->orderBy('due_on', 'asc')
            ->get();

        $pdf = PDF::loadView('reports.monthly', compact('monthlyReport'));

        $filename = 'monthly_report_' . now()->format('Y-m-d_H-i-s') . '.pdf';

        $directoryPath = storage_path('app/reports');

        if (!is_dir($directoryPath)) {
            mkdir($directoryPath, 0755, true);
        }

        $pdf->save("$directoryPath/$filename");

        Report::create([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'file_path' => $filename,
        ]);

        return response()->json([
            'message' => 'Monthly report generated successfully.',
            'file_path' => storage_path("app/reports/$filename"),
        ]);
    }
}
