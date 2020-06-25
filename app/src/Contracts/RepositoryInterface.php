<?php
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 9/25/18
 * Time: 12:49 PM
 */

namespace App\Src\Contracts;


/**
 * Interface RepositoryInterface
 * @package App\Interfaces
 */
interface RepositoryInterface
{

    /**
     * @return void
     */
    public function newQuery();

    /**
     * @return mixed
     */
    public function get();

    /**
     * @param null $key
     * @return mixed
     */
    public function orderBy($key, $dir = 'asc');

    /**
     * @param null $key
     * @param $data
     * @param $tags
     * @return mixed
     */

    public function with($relations);

    /**
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * @param $column
     * @param $query
     * @return mixed
     */
    public function findBy($column, $query);

    /**
     * @param $query
     * @return mixed
     */
    public function orWhere($query);

    /**
     * @param $query
     * @return mixed
     */
    public function where($query);

    /**
     * @param $array
     * @return mixed
     */
    public function whereIn($column,$array);

    /**
     * @param $relation
     * @param $closure
     * @return mixed
     */
    public function whereHas($relation, $closure);

    /**
     * @return mixed
     */
    public function all();

    /**
     * @param $data
     * @return mixed
     */
    public function create($data);

    /**
     * @param $data
     * @return mixed
     */
    public function save($data);

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data);

    /**
     * @param $id
     * @return boolean
     */
    public function delete($id);

    /**
     * @param $model
     * @return void
     */
    public function setModel($model);

    /**
     * @return mixed
     */
    public function getModel();
}