<?php

namespace App\Exports;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithTitle;

class EmployeeReportExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithColumnFormatting, WithTitle
{
    protected $filters;
    protected $user;

    public function __construct(array $filters = [], User $user)
    {
        $this->filters = $filters;
        $this->user = $user;
    }

    /**
     * Retorna a query base para o relatório
     */
    public function query()
    {
        $query = Employee::query()
            ->join('units', 'employees.unit_id', '=', 'units.id')
            ->join('brands', 'units.brand_id', '=', 'brands.id')
            ->join('economic_groups', 'brands.economic_group_id', '=', 'economic_groups.id')
            ->select([
                'employees.*',
                'units.trade_name as unit_name',
                'units.legal_name as unit_legal_name',
                'brands.name as brand_name',
                'economic_groups.name as economic_group_name',
                DB::raw('DATEDIFF(NOW(), employees.created_at) as days_in_company')
            ])
            ->where('economic_groups.user_id', $this->user->id);

        // Aplicar filtros
        $this->applyFilters($query);

        return $query;
    }

    /**
     * Define o cabeçalho do relatório
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nome',
            'Email',
            'CPF',
            'Grupo Econômico',
            'Bandeira',
            'Unidade (Nome Fantasia)',
            'Unidade (Razão Social)',
            'Data de Admissão',
            'Tempo na Empresa (dias)',
            'Status',
            'Última Atualização'
        ];
    }

    /**
     * Mapeia os dados para as colunas do Excel
     */
    public function map($employee): array
    {
        return [
            $employee->id,
            $employee->name,
            $employee->email,
            $this->formatCPF($employee->cpf),
            $employee->economic_group_name,
            $employee->brand_name,
            $employee->unit_name,
            $employee->unit_legal_name,
            $employee->created_at->format('d/m/Y'),
            $employee->days_in_company,
            $this->getEmployeeStatus($employee),
            $employee->updated_at->format('d/m/Y H:i:s')
        ];
    }

    /**
     * Define estilos para o Excel
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Cabeçalho em negrito
            
            'A1:L1' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'CCCCCC']
                ]
            ],
        ];
    }

    /**
     * Define formatos para as colunas
     */
    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_TEXT, // CPF
            'I' => NumberFormat::FORMAT_DATE_DDMMYYYY, // Data de Admissão
            'J' => NumberFormat::FORMAT_NUMBER, // Dias na empresa
            'L' => NumberFormat::FORMAT_DATE_DDMMYYYY . ' HH:MM:SS' // Última atualização
        ];
    }

    /**
     * Define o nome da aba do Excel
     */
    public function title(): string
    {
        return 'Relatório de Colaboradores';
    }

    /**
     * Aplica os filtros na query
     */
    protected function applyFilters($query)
    {
        $query->when($this->filters['economic_group_id'] ?? null, function ($q, $value) {
            $q->where('economic_groups.id', $value);
        })
        ->when($this->filters['brand_id'] ?? null, function ($q, $value) {
            $q->where('brands.id', $value);
        })
        ->when($this->filters['unit_id'] ?? null, function ($q, $value) {
            $q->where('units.id', $value);
        })
        ->when($this->filters['date_start'] ?? null, function ($q, $value) {
            $q->where('employees.created_at', '>=', $value);
        })
        ->when($this->filters['date_end'] ?? null, function ($q, $value) {
            $q->where('employees.created_at', '<=', $value);
        })
        ->when($this->filters['search'] ?? null, function ($q, $value) {
            $q->where(function ($query) use ($value) {
                $query->where('employees.name', 'like', "%{$value}%")
                      ->orWhere('employees.email', 'like', "%{$value}%")
                      ->orWhere('employees.cpf', 'like', "%{$value}%");
            });
        });
    }

    /**
     * Formata o CPF
     */
    protected function formatCPF($cpf)
    {
        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $cpf);
    }

    /**
     * Retorna o status do colaborador
     */
    protected function getEmployeeStatus($employee)
    {
        $daysInCompany = $employee->days_in_company;
        
        if ($daysInCompany <= 90) {
            return 'Em Experiência';
        } elseif ($daysInCompany <= 365) {
            return 'Até 1 ano';
        } elseif ($daysInCompany <= 730) {
            return '1 a 2 anos';
        } else {
            return 'Mais de 2 anos';
        }
    }
}