<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PoliceCategoryRequest;
use App\Http\Resources\Admin\PolicyCategoryResource;
use App\Models\PolicyCategory;
use App\Services\PolicyCategoryService;
use Illuminate\Http\Request;

class PolicyManagementController extends Controller
{
    protected PolicyCategoryService $policyCategoryService;

    public function __construct(PolicyCategoryService $policyCategoryService)
    {
        $this->policyCategoryService = $policyCategoryService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = $this->policyCategoryService->getAll();
        return PolicyCategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PoliceCategoryRequest $request)
    {
        $validated = $request->validated();
        $category = $this->policyCategoryService->storeCategory($request, $validated);
        return response_success('Policy category created successfully.', $category, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(PolicyCategory $policy)
    {
        return new PolicyCategoryResource($policy);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PolicyCategory $policy )
    {
        $this->policyCategoryService->delete($policy->id);
        return response_success('Policy category deleted successfully');
    }
}
