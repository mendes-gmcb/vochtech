<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Jobs\GenerateEmployeeReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
  /**
   * Gera relatório de colaboradores com diversos filtros e métricas
   */
  public function generateReport(Request $request)
  {
    $cacheKey = 'employee_report_' . md5(json_encode($request->all()));

    // Verifica se existe cache do relatório
    if (Cache::has($cacheKey) && !$request->refresh) {
      return response()->json([
        'data' => Cache::get($cacheKey),
        'from_cache' => true
      ]);
    }

    $query = DB::table('employees')
      ->join('units', 'employees.unit_id', '=', 'units.id')
      ->join('brands', 'units.brand_id', '=', 'brands.id')
      ->join('economic_groups', 'brands.economic_group_id', '=', 'economic_groups.id')
      ->select([
        'economic_groups.name as economic_group',
        'brands.name as brand',
        'units.trade_name as unit',
        DB::raw('COUNT(*) as total_employees'),
        DB::raw('COUNT(CASE WHEN employees.created_at >= ? THEN 1 END) as new_employees', [now()->subDays(30)]),
        DB::raw('COUNT(CASE WHEN employees.created_at >= ? THEN 1 END) as employees_last_quarter', [now()->subMonths(3)]),
        DB::raw('AVG(CASE WHEN employees.created_at < ? THEN DATEDIFF(NOW(), employees.created_at) END) as avg_tenure_days', [now()->subDays(30)])
      ]);

    // Aplicando filtros
    $this->applyFilters($query, $request);

    // Agrupamento
    $query->groupBy('economic_groups.id', 'economic_groups.name', 'brands.id', 'brands.name', 'units.id', 'units.trade_name');

    // Ordenação
    if ($request->sort_by && $request->sort_direction) {
      $query->orderBy($request->sort_by, $request->sort_direction);
    } else {
      $query->orderBy('economic_groups.name')
        ->orderBy('brands.name')
        ->orderBy('units.trade_name');
    }

    $report = $query->get();

    // Calcula métricas adicionais
    $report = $this->calculateAdditionalMetrics($report);

    // Cache do resultado por 1 hora
    Cache::put($cacheKey, $report, now()->addHour());

    return response()->json([
      'data' => $report,
      'from_cache' => false
    ]);
  }

  /**
   * Aplica filtros na query do relatório
   */
  private function applyFilters($query, Request $request)
  {
    $query->when($request->economic_group_id, function ($q, $value) {
      $q->where('economic_groups.id', $value);
    })
      ->when($request->brand_id, function ($q, $value) {
        $q->where('brands.id', $value);
      })
      ->when($request->unit_id, function ($q, $value) {
        $q->where('units.id', $value);
      })
      ->when($request->date_start, function ($q, $value) {
        $q->where('employees.created_at', '>=', $value);
      })
      ->when($request->date_end, function ($q, $value) {
        $q->where('employees.created_at', '<=', $value);
      })
      ->when($request->search, function ($q, $value) {
        $q->where(function ($query) use ($value) {
          $query->where('employees.name', 'like', "%{$value}%")
            ->orWhere('employees.email', 'like', "%{$value}%")
            ->orWhere('employees.cpf', 'like', "%{$value}%");
        });
      });
  }

  /**
   * Calcula métricas adicionais para o relatório
   */
  private function calculateAdditionalMetrics($report)
  {
    $total = $report->sum('total_employees');

    return $report->map(function ($item) use ($total) {
      $item->employee_percentage = $total > 0 ?
        round(($item->total_employees / $total) * 100, 2) : 0;

      $item->turnover_rate = $item->total_employees > 0 ?
        round(($item->new_employees / $item->total_employees) * 100, 2) : 0;

      return $item;
    });
  }

  /**
   * Exporta o relatório para Excel
   */
  public function exportToExcel(Request $request)
  {
    // Dispara job para geração assíncrona do relatório
    GenerateEmployeeReport::dispatch($request->all(), $request->user())->onQueue('reports');

    return Inertia::render('Dashboard');
  }

  /**
   * Retorna dados para dashboard
   */
  public function index()
  {
    $cacheKey = 'employee_dashboard';

    $data = Cache::remember($cacheKey, now()->addHours(1), function () {
      return [
        'total_employees' => Employee::count(),
        'employees_by_group' => DB::table('employees')
          ->join('units', 'employees.unit_id', '=', 'units.id')
          ->join('brands', 'units.brand_id', '=', 'brands.id')
          ->join('economic_groups', 'brands.economic_group_id', '=', 'economic_groups.id')
          ->groupBy('economic_groups.id', 'economic_groups.name')
          ->select(
            'economic_groups.name',
            DB::raw('COUNT(*) as total')
          )
          ->get(),
        'new_employees_last_30_days' => Employee::where('created_at', '>=', now()->subDays(30))->count(),
        'employees_by_brand' => DB::table('employees')
          ->join('units', 'employees.unit_id', '=', 'units.id')
          ->join('brands', 'units.brand_id', '=', 'brands.id')
          ->groupBy('brands.id', 'brands.name')
          ->select(
            'brands.name',
            DB::raw('COUNT(*) as total')
          )
          ->get()
      ];
    });

    return Inertia::render('Dashboard', [
      'data' => $data,
    ]);
  }
}
