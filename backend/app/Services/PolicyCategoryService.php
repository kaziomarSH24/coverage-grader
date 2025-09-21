<?php

namespace App\Services;

use App\Filters\GlobalSearchFilter;
use App\Services\BaseService;
use App\Models\PolicyCategory;
use App\Traits\FileUploadTrait;
use App\Traits\ManagesData;
use Spatie\QueryBuilder\AllowedFilter;

class PolicyCategoryService extends BaseService
{
    use ManagesData, FileUploadTrait;
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
            $data['logo_url'] = $this->handleFileUpload($requset, 'logo_url', 'policy_categories');
        }
        return $this->storeOrUpdate($data, new $this->modelClass);
    }

}
