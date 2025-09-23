<?php

namespace App\Services\Admin;

use App\Services\BaseService;
use App\Models\InsuranceProvider;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\GlobalSearchFilter;
use App\Traits\FileUploadTrait;

class InsuranceProviderService extends BaseService
{
    use FileUploadTrait;
    /**
     * The model class name.
     *
     * @var string
     */
    protected string $modelClass = InsuranceProvider::class;

    public function __construct()
    {
        // Ensure BaseService initializes the model instance
        parent::__construct();
    }

    // Define allowed filters
     protected function getAllowedFilters(): array
    {
        return [
            'name',
            AllowedFilter::exact('status'),
        ];
    }

    // Define allowed includes relationships
     protected function getAllowedIncludes(): array
     {
        return [
            'policyCategories',
            'states',
        ];
     }

     // Define allowed sorts
     protected function getAllowedSorts(): array
     {
        return [
            'id',
            'name',
            'created_at',
        ];
     }

    //** ---- added specific logic here ----

    //store provider
    public function storeProvider($request, array $data){
        //image handle
        if ($request->hasFile('logo_url')) {
            $data['logo_url'] = $this->handleFileUpload($request, 'logo_url', 'insurance_providers',null, null, 90, true);
        }

        //relation
        $relations = [
            'policyCategories' => $data['policies'] ?? [],
            'states' => $data['states'] ?? [],
        ];

        unset($data['states'], $data['policies']);

        $provider = $this->create($data, $relations);
        return $provider->load('states', 'policyCategories');
    }

    //*update provider
    public function updateProvider($provider, $request, array $data){
        //image handle
        if ($request->hasFile('logo_url')) {
            //delete old image and upload new one
            $this->deleteFile($provider->logo_url);
            $data['logo_url'] = $this->handleFileUpload($request, 'logo_url', 'insurance_providers', $provider->logo_url, null, 90, true);
        }

        //relation
        $relations = [
            'policyCategories' => $data['policies'] ?? [],
            'states' => $data['states'] ?? [],
        ];

        unset($data['states'], $data['policies']);

        $provider = $this->update($provider->id, $data, $relations);
        return $provider->load('states', 'policyCategories');
    }

    //*delete provider
    public function deleteProvider($provider){
        //delete logo file
        if($provider->logo_url){
            $this->deleteFile($provider->logo_url);
        }
        return $this->delete($provider->id);
    }
}
