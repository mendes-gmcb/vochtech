<?php

namespace App\Jobs;

use App\Exports\EmployeeReportExport;
use App\Models\User;
use App\Notifications\ReportGenerated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class GenerateEmployeeReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $filters;
    public $user;
    public $job_id;

    public function __construct(array $filters, User $user)
    {
        $this->filters = $filters;
        $this->user = $user;
        $this->job_id = uniqid('report_');
    }

    public function handle()
    {
        $filename = 'employee_report_' . date('Y-m-d_His') . '.xlsx';
        
        Excel::store(
            new EmployeeReportExport($this->filters, $this->user),
            $filename,
            'public',
        );

        // Notifica o usuário que o relatório está pronto
        $this->user->notify(new ReportGenerated($filename));
    }
}
