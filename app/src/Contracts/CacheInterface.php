<?php
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 9/25/18
 * Time: 12:49 PM
 */

namespace App\Src\Contracts;


/**
 * Interface CacheInterface
 * @package App\Src\Contracts
 */
interface CacheInterface
{
    public function put($data,$key,$tags=[]);

    public function set($closure,$key,$tags=[]);

    public function get($key,$tags=[]);

    public function delete($key,$tags=[]);

    public function flush($key,$tags=[]);

    public function isTaggeable();
}