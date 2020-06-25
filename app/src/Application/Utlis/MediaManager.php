<?php

namespace App\Src\Application\Utils;

use App\Exceptions\FileException;
use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Media;

/**
 * Created by PhpStorm.
 * User: penna
 * Date: 3/19/19
 * Time: 2:24 PM
 */
/*Este trait trabaja en conjunto con plank/medias */

trait MediaManager
{
    protected $parameters;

    public static function bootMediaManager()
    {
        $traits = array_map(function ($trait) {
            return class_basename($trait);
        }, get_declared_traits());

        if (!in_array('SetParameters', $traits))
            throw new FileException(__('exceptions.dependencies.parameters_trait', ['function' => 'medias sync']));

        static::saved(function (Model $model) {
            $medias = @$model->parameters['medias'];
            if ($medias) {
                $new = @$medias['loaded'];
                $model->syncMedias($model, $medias);
                if ($new)
                    $model->attachMany($medias['loaded']);
                unset($model->parameters['medias']);
            }
        });
    }

    public function attachMany($uploaded)
    {
        foreach ($uploaded as $group => $value) {
            foreach ($value as $type => $media) {
                if ($type !== 'img')
                    $this->attachMedia($media, [$group, $type]);
                else {
                    array_walk($media, function ($value, $dimension) use ($group, $type, $media) {
                        $this->attachMedia($value, [$group, $type, $dimension]);
                    });
                }
            }
        }
    }

    public function syncMedias(Model $model, $medias)
    {
        foreach ($medias as $group => $value) {
            if (!isset($value['uploaded']))
                continue;
            $medias = collect(json_decode($value['uploaded'], true));
            $to_attach = collect([]);
            $attached = $model->getMedia($group)->pluck('id');
            $medias->each(function ($m) use ($model, $group, &$to_attach, $attached) {
                $query = Media::where('filename', $m['filename'])->whereIn('id', $attached);
                $query->update([
                    'attributes' => $m['attributes']
                ]);
                $to_attach = $to_attach->concat($query->get()->pluck('id'));
            });
            $to_detach = $attached->diff($to_attach);
//            $model->detachMedia($to_detach);
            $model->media()->whereIn('id', $to_detach)->get()->each->delete();
        }
    }

    public function loadMedias($tags, $match = true)
    {
        return $this->getMedia($tags, $match);
    }
}