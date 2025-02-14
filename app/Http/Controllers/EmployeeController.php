<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Repositories\BrandRepository;
use App\Repositories\EconomicGroupRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\UnitRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    public function __construct(
        protected EmployeeRepository $employeeRepository,
        protected UnitRepository $unitRepository,
        protected BrandRepository $brandRepository,
        protected EconomicGroupRepository $economicGroupRepository
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $groups = $this->economicGroupRepository->list($request->user());
        $groupIds = $groups->pluck('id')->toArray();

        $brands = $this->brandRepository->list($groupIds);
        $brandIds = $brands->pluck('id')->toArray();

        $units = $this->unitRepository->list($brandIds);
        $unitIds = $units->pluck('id')->toArray();

        $employees = $this->employeeRepository->listPaginate($unitIds);

        return Inertia::render('Employee/index', [
            'groups' => $groups,
            'brands' => $brands,
            'units' => $units,
            'employees' => $employees,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        // Gate authorization
        $data = $request->validated();
        $this->employeeRepository->create($data);

        return Redirect::route('employees.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        return Inertia::render('Employee/Edit', [
            'employee' => $employee,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $this->employeeRepository->update($request->validated(), $employee->id);

        return Redirect::route('employees.edit', $employee->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $this->employeeRepository->delete($employee->id);

        return Redirect::route('employees.index');
    }
}
