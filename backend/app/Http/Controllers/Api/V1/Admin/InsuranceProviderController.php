<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InsuranceProviderRequest;
use App\Http\Resources\Admin\InsuranceProviderResource;
use App\Models\InsuranceProvider;
use App\Services\Admin\InsuranceProviderService;
use Illuminate\Http\Request;

class InsuranceProviderController extends Controller
{

    protected InsuranceProviderService $insuranceService;
    public function __construct(InsuranceProviderService $insuranceService)
    {
        $this->insuranceService = $insuranceService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $providers = $this->insuranceService->getAll();
        return InsuranceProviderResource::collection($providers);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(InsuranceProviderRequest $request)
    {
        $validated = $request->validated();
        $provider = $this->insuranceService->storeProvider($request, $validated);
        return response_success('Insurance provider created successfully.', $provider, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(InsuranceProvider $provider)
    {
        return new InsuranceProviderResource($provider->load(['policyCategories', 'states']));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(InsuranceProviderRequest $request, InsuranceProvider $provider)
    {
        $validated = $request->validated();
        $provider = $this->insuranceService->updateProvider($provider, $request, $validated);
        return response_success('Insurance provider updated successfully.', $provider);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InsuranceProvider $provider )
    {
        $this->insuranceService->deleteProvider($provider); 
        return response_success('Insurance provider deleted successfully');
    }

}
