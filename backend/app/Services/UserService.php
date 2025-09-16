<?php

namespace App\Services;

use App\Filters\GlobalSearchFilter;
use App\Services\BaseService;
use App\Models\User;
use Spatie\QueryBuilder\AllowedFilter;

class UserService extends BaseService
{
    /**
     * The model class name.
     *
     * @var string
     */
    protected string $modelClass = User::class;

    public function __construct()
    {
        // Ensure BaseService initializes the model instance
        parent::__construct();

        $this->allowedFilters = [
            // New: Added a global filter named 'search'
            // This will search both 'name' and 'email' columns
            AllowedFilter::custom('search', new GlobalSearchFilter, 'name,email'),

            // Regular filters are also included
            'name',
            'email',
        ];
    }



    /**
    * Which fields are allowed to be sorted by.
     * @var array
     */
    protected array $allowedSorts = [
        'id',
        'name',
        'created_at',
    ];

    /**
    * Which relationships are allowed to be loaded.
     * @var array
     */
    protected array $allowedIncludes = [
        'roles',
    ];


}
