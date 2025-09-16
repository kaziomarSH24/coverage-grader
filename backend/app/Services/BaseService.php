<?php

namespace App\Services;

use App\Traits\Cacheable;
use App\Traits\ManagesData;
use Illuminate\Database\Eloquent\Model;
use Closure;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Class BaseService
 *
 * An abstract base service layer for handling common CRUD operations.
 * Services extending this class must set the `$modelClass` property to the fully-qualified
 * class name of the Eloquent model they will manage.
 *
 * This class uses the `ManagesData` trait to centralize create/update logic.
 *
 * @package App\Services
 */
abstract class BaseService
{
    use ManagesData, Cacheable;

    /**
     * The fully qualified class name of the model.
     *
     * Example:
     *   protected string $modelClass = \App\Models\User::class;
     *
     * @var string
     */
    protected string $modelClass;

    /**
     * The model instance for performing queries.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected Model $model;

    // --- Query Builder Properties ---
    protected array $allowedFilters = [];
    protected array $allowedIncludes = [];
    protected array $allowedSorts = [];

    /**
     * BaseService constructor.
     *
     * Resolves the model class from the Laravel service container.
     */
    public function __construct()
    {
        $this->model = app($this->modelClass);
    }

    /**
     * Retrieve all records with optional relationships, pagination, dynamic ordering, and custom queries.
     *
     * @param Closure|null  $queryCallback   A closure to apply custom query
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
 public function getAll(?Closure $queryCallback = null)
    {
        $callback = function () use ($queryCallback) {
            // Prothome, ekta base Eloquent query builder toiri kora hocche.
            $baseQuery = $this->model->query();

            // Jodi kono custom query callback pathano hoy, sheti-ke apply kora hocche.
            if ($queryCallback) {
                $queryCallback($baseQuery);
            }

            // Ebar, ei (potenshial-vabe poribortito) builder-ti-ke Spatie QueryBuilder-e pathano hocche.
            return QueryBuilder::for($baseQuery)
                ->allowedFilters($this->allowedFilters)
                ->allowedIncludes($this->allowedIncludes)
                ->allowedSorts($this->allowedSorts)
                ->paginate(request()->input('per_page', 15))
                ->appends(request()->query());
        };

        // func_get_args() shob argument-ke ekta array hishebe return kore
        return $this->cache(__FUNCTION__, func_get_args(), $callback);
    }

    /**
     * Retrieve a single record by its primary key.
     *
     * @param int   $id    The primary key value.
     * @param array $with  Relationships to eager load.
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getById(int|string $id, array $with = [])
    {
        return $this->cache(__FUNCTION__, func_get_args(), function () use ($id, $with) {
            return $this->model->with($with)->findOrFail($id);
        });
    }

    /**
     * Retrieve a single record by a specific column and value, or throw an exception if not found.
     *
     * @param String $column
     * @param $value
     * @param array $with
     * @return \Illuminate\Database\Eloquent\Model
     */
     public function findByOrFail(string $column, $value, array $with = [])
    {
        return $this->cache(__FUNCTION__, func_get_args(), function () use ($column, $value, $with) {
            return $this->model->with($with)->where($column, $value)->firstOrFail();
        });
    }

    /**
     * Create a new record in the database.
     *
     * @param array        $data                  Data to be saved.
     * @param array        $relations             Related models to sync or attach.
     * @param Closure|null $transactionalCallback Optional transactional logic after save.
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data, array $relations = [], ?Closure $transactionalCallback = null)
    {
        return $this->storeOrUpdate($data, new $this->modelClass, $relations, $transactionalCallback);
    }

    /**
     * Update an existing record in the database.
     *
     * @param int          $id                    Primary key of the record to update.
     * @param array        $data                  Data to be updated.
     * @param array        $relations             Related models to sync or attach.
     * @param Closure|null $transactionalCallback Optional transactional logic after update.
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(int|string $id, array $data, array $relations = [], ?Closure $transactionalCallback = null)
    {
        $record = $this->getById($id);
        return $this->storeOrUpdate($data, $record, $relations, $transactionalCallback);
    }

    /**
     * Delete a record by its primary key.
     *
     * @param int $id Primary key of the record to delete.
     * @return bool
     */
    public function delete(int|string $id): bool
    {
        return $this->getById($id)->delete();
    }
}
