<?php
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 3/19/19
 * Time: 11:03 AM
 */

namespace App\Src\Domain\Repositories;


use App\Exceptions\RepositoryException;
use App\Src\Contracts\CacheInterface;
use App\Src\Contracts\RepositoryInterface;

class BaseRepository implements RepositoryInterface
{
    protected $concrete;
    protected $cache;

    protected $events_flush_cache = false;

    /**
     * BaseRepository constructor.
     * @param $especification
     */
    public function __construct(RepositoryInterface $especification, CacheInterface $cache = null)
    {
        $this->concrete = $especification;

        //Mecanismo de cache (si existe)
        $this->cache = $cache;
    }

    public function __call($method, $arguments)
    {
        $regexp = '/^findBy(?<column>[A-Z]\w+)$/';
        if (preg_match($regexp, $method, $matches) && count($arguments) == 1) {
            array_unshift($arguments, strtolower($matches['column']));
            return call_user_func_array(array($this, 'findBy'), $arguments);
        }

        $regexp = '/^where(?<column>[A-Z]\w+)$/';
        if (preg_match($regexp, $method, $matches) && count($arguments) == 1) {
            array_push($arguments, strtolower($matches['column']));
            return call_user_func_array(array($this, 'where'), $arguments);
        } else
            throw new RepositoryException(__('exceptions.repository.method_doesnt_exist', ['method' => $method]));
    }

    public function newQuery()
    {
        $this->concrete->newQuery();
    }

    public function get($key = null)
    {
        return $this->concrete->get($key);
    }

    public function orderBy($key, $dir = 'asc')
    {
        $this->concrete->orderBy($key, $dir);
    }

    public function with($relations)
    {
        return $this->concrete->with($relations);
    }

    public function find($id)
    {
        return $this->concrete->find($id);
    }

    public function findBy($column, $query)
    {
        return $this->concrete->findBy($column, $query);
    }

    public function where($query, $column = null)
    {
        return $this->concrete->where($query, $column);
    }

    public function orWhere($query)
    {
        return $this->concrete->orWhere($query);
    }

    public function whereIn($column, $array)
    {
        $this->concrete->whereIn($column, $array);
    }

    public function whereHas($relation, $closure)
    {
        return $this->concrete->whereHas($relation, $closure);
    }

    public function create($data)
    {
        return $this->concrete->create($data);
    }

    public function save($data, $load = true)
    {
        return $this->concrete->save($data, $load);
    }

    public function update($id, $data, $load = true)
    {
        return $this->concrete->update($id, $data, $load);
    }

    public function delete($id)
    {
        return $this->concrete->delete($id);
    }

    public function all()
    {
        return $this->concrete->all();
    }

    public function setModel($model)
    {
        $this->concrete->setModel($model);
    }

    public function getModel()
    {
        return $this->concrete->getModel();
    }

    public function toggle($field, $ids)
    {
        return $this->concrete->toggle($field, $ids);
    }

    public function loadMedias($id, $tags, $match = true)
    {
        return $this->concrete->loadMedias($id, $tags, $match);
    }

}