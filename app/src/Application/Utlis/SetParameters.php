<?php

namespace App\Src\Application\Utils;

use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Media;

/**
 * Created by PhpStorm.
 * User: penna
 * Date: 3/19/19
 * Time: 2:24 PM
 */

/*Este trait trabaja en conjunto con plank/medias y SetParameters */

trait SetParameters
{
    protected $parameters;
    protected static $fill_relationships = false;

    public static function bootSetParameters()
    {
        $traits = array_map(function ($trait) {
            return class_basename($trait);
        }, get_declared_traits());
        if (in_array('FillRelationships', $traits))
            self::$fill_relationships = true;
    }

    public function save(array $attributes = [], $load = true)
    {
        if (!empty($attributes)) {
            $this->parameters = $attributes;
            parent::save();
            if (self::$fill_relationships) $this->fillRelations($attributes);
            if ($load)
                return $this->load(self::$relationshipes);

            return $this;
        }
    }
}