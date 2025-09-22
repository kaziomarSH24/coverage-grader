<?php

namespace App\Services\Admin;

use App\Filters\GlobalSearchFilter;
use App\Services\BaseService;
use App\Models\PolicyCategory;
use App\Traits\FileUploadTrait;
use Spatie\QueryBuilder\AllowedFilter;

class PolicyCategoryService extends BaseService
{
    use FileUploadTrait;
    /**
     * The model class name.
     *
     * @var string
     */
    protected string $modelClass = PolicyCategory::class;

    public function __construct()
    {
        // Ensure BaseService initializes the model instance
        parent::__construct();
    }

     protected function getAllowedFilters(): array
    {
        return [
            AllowedFilter::custom('search', new GlobalSearchFilter, 'name','slug'),
            'name',
            'slug',
            AllowedFilter::exact('status'),
        ];
    }
     protected function getAllowedIncludes(): array
     {
        return [
            //
        ];
     }
     protected function getAllowedSorts(): array
     {
        return [
            'id',
            'name',
            'created_at',
        ];
     }

    //store category
    public function storeCategory($requset, array $data){
        //generate slug
        $data['slug'] = generateUniqueSlug(new $this->modelClass, $data['name']);
        //image upload
        if ($requset->hasFile('logo_url')) {
            $data['logo_url'] = $this->handleFileUpload($requset, 'logo_url', 'policy_categories',null, null, 90, true);
        }
        return $this->create($data);
    }

    //update category
    public function updateCategory($policy, $requset, array $data){
        //generate slug
        $data['slug'] = generateUniqueSlug(new $this->modelClass, $data['name'], $policy->id);
       //image hadle
        if ($requset->hasFile('logo_url')) {
            //remove old image
            $this->deleteFile($policy->logo_url);
            //upload new image
            $data['logo_url'] = $this->handleFileUpload($requset, 'logo_url', 'policy_categories',null, null, 90, true);
        }
        return $this->update($policy->id, $data);
    }

}
