<?php

namespace App\Src\Application\Utils;
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 3/19/19
 * Time: 2:24 PM
 */
trait FileManager
{

    //Métodos para el manejo de ficheros usando el atributo $mediable q se inyecta desde el IoC
    //y contiene el vendor para la gestion de ficheros

    public function upload($file, $attributes, $mimes, $directory, $disk = 'public')
    {
        return $this->mediable->upload($file, $attributes, $mimes, $directory, $disk);
    }

    public function uploadMany($medias)
    {
        $uploaded = [];
        foreach ($medias as $group => $value) {
            if (!isset($value['files']))
                continue;

            foreach ($value['files'] as $key => $file) {
                $uploaded [$group] = $this->upload(
                    $file,
                    $value['attributes'][$key],
                    json_decode($value['mimes'], true),
                    strtolower(class_basename($this->repository->getModel())));
            }
        }

        return $uploaded;
    }
}