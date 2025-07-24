<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomerExport;
// use Barryvdh\DomPDF\Facade\Pdf; // Uncomment if using PDF

class GenerateReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $type;
    protected $recipientEmail;

    /**
     * Create a new job instance.
     */
    public function __construct($type = 'excel', $recipientEmail = null)
    {
        $this->type = $type;
        $this->recipientEmail = $recipientEmail;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->type === 'excel') {
            $file = Excel::raw(new CustomerExport, \Maatwebsite\Excel\Excel::XLSX);
            // Store or email the file as needed
        } else if ($this->type === 'pdf') {
            // $pdf = Pdf::loadView('reports.customer_cases', []);
            // $content = $pdf->output();
            // Store or email the PDF as needed
        }
        // Optionally: notify user when report is ready
    }
}
