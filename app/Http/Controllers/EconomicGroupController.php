<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEconomicGroupRequest;
use App\Http\Requests\UpdateEconomicGroupRequest;
use App\Models\EconomicGroup;
use App\Repositories\EconomicGroupRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class EconomicGroupController extends Controller
{
    public function __construct(protected EconomicGroupRepository $economicGroupRepository)
    {
        
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $groups = $this->economicGroupRepository->list($user);

        return Inertia::render('EconomicGroups/index', [
            'paginatedGroups' => $groups
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
    public function store(StoreEconomicGroupRequest $request)
    {
        // Gate authorization
        $data = [...$request->validated(), "user_id" => Auth::user()->id];
        $this->economicGroupRepository->create($data);

        return Redirect::route('economic.group.list');
    }

    /**
     * Display the specified resource.
     */
    public function show(EconomicGroup $economicGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EconomicGroup $economicGroup)
    {
        return Inertia::render('EconomicGroups/Edit', [
            'group' => $economicGroup,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEconomicGroupRequest $request, EconomicGroup $economicGroup)
    {
        $this->economicGroupRepository->update($request->validated(), $economicGroup->id);

        return Redirect::route('economic.group.edit', $economicGroup->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EconomicGroup $economicGroup)
    {
        $this->economicGroupRepository->delete($economicGroup->id);

        return Redirect::route('economic.group.list');
    }
}
