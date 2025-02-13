<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Models\Brand;
use App\Repositories\BrandRepository;
use App\Repositories\EconomicGroupRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class BrandController extends Controller
{
    public function __construct(protected BrandRepository $brandRepository, protected EconomicGroupRepository $economicGroupRepository)
    {
        
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $groups = $this->economicGroupRepository->list($request->user());
        $groupIds = $groups->pluck('id')->toArray();
        
        $brands = $this->brandRepository->listPaginate($groupIds);
        
        return Inertia::render('Brand/index', [
            'brands' => $brands,
            'groups' => $groups,
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
    public function store(StoreBrandRequest $request)
    {
        // Gate authorization
        $data = $request->validated();
        $this->brandRepository->create($data);

        return Redirect::route('brand.list');
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        // 
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        return Inertia::render('Brand/Edit', [
            'brand' => $brand,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $this->brandRepository->update($request->validated(), $brand->id);

        return Redirect::route('brand.edit', $brand->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        $this->brandRepository->delete($brand->id);

        return Redirect::route('brand.list');
    }
}
