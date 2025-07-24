<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\CustomerExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportsController extends Controller
{
    public function userPerformance(Request $request)
    {
        // Query/filter logic here
        return view('reports.user_performance');
    }

    public function customerCases(Request $request)
    {
        // Query/filter logic here
        return view('reports.customer_cases');
    }

    public function departmentReports(Request $request)
    {
        // Query/filter logic here
        return view('reports.department_reports');
    }

    public function exportExcel(Request $request)
    {
        // Export logic here (example: customers)
        return Excel::download(new CustomerExport, 'report.xlsx');
    }

    public function exportPDF(Request $request)
    {
        // Export logic here (example: customers)
        $pdf = Pdf::loadView('reports.customer_cases', []); // Pass data as needed
        return $pdf->download('report.pdf');
    }
}
