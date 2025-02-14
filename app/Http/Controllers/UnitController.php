<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Models\Unit;
use App\Repositories\BrandRepository;
use App\Repositories\EconomicGroupRepository;
use App\Repositories\UnitRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class UnitController extends Controller
{
    public function __construct(
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

        $units = $this->unitRepository->listPaginate($brandIds);

        return Inertia::render('Unit/index', [
            'brands' => $brands,
            'units' => $units,
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
    public function store(StoreUnitRequest $request)
    {
        // Gate authorization
        $data = $request->validated();
        $this->unitRepository->create($data);

        return Redirect::route('unit.list');
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit)
    {
        return Inertia::render('Unit/Edit', [
            'unit' => $unit,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUnitRequest $request, Unit $unit)
    {
        $this->unitRepository->update($request->validated(), $unit->id);

        return Redirect::route('unit.edit', $unit->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        $this->unitRepository->delete($unit->id);

        return Redirect::route('unit.list');
    }
}
