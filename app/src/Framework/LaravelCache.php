<?php
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 3/12/19
 * Time: 3:17 PM
 */

namespace App\Src\Framework;

use App\Src\Contracts\CacheInterface;
use Illuminate\Support\Facades\Cache as Cache;

class LaravelCache implements CacheInterface
{
    protected $lifetime;

    public function __construct($lifetime = 60)
    {
        $this->lifetime = $lifetime;
    }

    public function put($data, $key, $tags = [])
    {
        if ($this->isTaggeable())
            Cache::tags($tags)->put($key, $data, $this->lifetime);
        else
            Cache::put($key, $data, $this->lifetime);
    }

    public function set($closure, $key, $forever = false, $tags = [])
    {
        if ($this->isTaggeable()) {
            if ($forever)
                Cache::tags($tags)->rememberForever($key, function() use($closure) {
                    return $closure();
                }, $this->lifetime);
            Cache::tags($tags)->remember($key, function() use($closure) {
                return $closure();
            }, $this->lifetime);
        } else
            if ($forever)
                Cache::rememberForever($key, function() use($closure) {
                    return $closure();
                }, $this->lifetime);

        Cache::remember($key, function() use($closure) {
            return $closure();
        }, $this->lifetime);
    }

    public function get($key, $tags = [])
    {
        if ($this->isTaggeable())
            return Cache::tags($tags)->get($key);

        return Cache::get($key);
    }

    public function delete($key, $tags = [])
    {
        if ($this->isTaggeable())
            Cache::tags($tags)->forget($key);
        else
            Cache::forget($key);
    }

    public function flush($key, $tags = [])
    {
        if ($this->isTaggeable())
            Cache::tags($tags)->flush();
        else
            Cache::flush();
    }

    public function isTaggeable()
    {
        return in_array(config('cache.default'), ['file', 'database']) ? false : true;
    }
}