<?php
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 3/18/19
 * Time: 9:43 PM
 */

namespace App\Src\Framework;


use App\Exceptions\RepositoryException;
use App\Src\Contracts\CacheInterface;
use App\Src\Contracts\RepositoryInterface;
use Illuminate\Support\Facades\DB;

class EloquentRepository implements RepositoryInterface
{
    protected $model;
    protected $builder;
    protected $relations = [];

    /**
     * EloquentRepository constructor.
     * @param $model
     * @param CacheInterface|null $cache
     */
    public function __construct($model = null)
    {
        $this->model = $model;
    }

    public function newQuery()
    {
        $this->builder = $this->model::query();
    }

    public function get()
    {
        $data = $this->builder->with($this->relations)->get();
        $this->newQuery();
        return $data;
    }

    public function orderBy($key, $dir = 'asc')
    {
        $this->builder = $this->builder->orderBy($key, $dir);
        return $this;
    }

    public function with($relations)
    {
        $this->relations = $relations;
        return $this;
    }

    public function find($id)
    {
        return $this->model::with($this->relations)->findOrFail($id);
    }

    public function findBy($column, $query)
    {
        return $this->builder->with($this->relations)->where($column, 'like', '%' . $query . '%')->limit(1)->first();
    }

    public function where($query, $column = null)
    {
        if (!$column)
            $this->builder = $this->builder->where($query);
        else
            $this->builder = $this->builder->where($column, $query);

        return $this;
    }

    public function orWhere($query)
    {
        $this->builder = $this->builder::orWhere($query);
        return $this;
    }

    public function whereIn($column, $array)
    {
        $this->builder = $this->builder->whereIn($column, $array);
        return $this;
    }

    public function whereHas($relation, $closure)
    {
        $this->builder = $this->builder::whereHas($relation, $closure());
        return $this;
    }

    public function all()
    {
        return $this->model::all();
    }

    public function create($data)
    {
        return $this->model::create($data);
    }

    public function save($data, $load = true)
    {
        return (new $this->model($data))->save($data, $load);
    }

    public function update($id, $data, $load = true)
    {
        $model = $this->find($id);
        return $model->fill($data)->save($data, $load);
    }

    public function delete($id)
    {
        $model = $this->find($id);
        $this->model::destroy($id);
        return $model;
    }

    public function setModel($model)
    {
        $this->model = $model;

        //Seteo el valor del builder con un nuevo query pues desde el IoC estoy llamando un singleton
        $this->newQuery();
    }

    public function getModel()
    {
        return $this->model;
    }

    public function toggle($field, $ids)
    {
        $table = (new $this->model())->getTable();
        $imploded = implode(',', $ids);
        DB::statement("update $table set $field = not($field) where id in ($imploded)");
        return $this->whereIn('id', $ids)->get()->pluck($field, 'id');
    }

    public function loadMedias($id, $tags, $match = true)
    {
        try {
            return $this->find($id)->loadMedias($tags, $match);
        } catch (\Exception $e) {
            throw new RepositoryException(__('exceptions.dependencies.media_manager_trait', ['function' => 'loadMedias']));
        }
    }

}