<?php
/**
 * Created by PhpStorm.
 * User: penna
 * Date: 3/12/19
 * Time: 3:17 PM
 */

namespace App\Src\Framework;

use App\Exceptions\FileException;
use App\Exceptions\TransactionException;
use App\Src\Contracts\Mediable;
use Exception;
use Facades\Plank\Mediable\MediaUploader;
use Intervention\Image\Facades\Image;
use Plank\Mediable\Exceptions\MediaUploadException;
use Plank\Mediable\Media;
use Plank\Mediable\SourceAdapters\SourceAdapterFactory;

class LaravelMediable implements Mediable
{


    protected $target_mime_type;

    /**
     * @param string $group
     * @param $file
     * @param $attributes
     * @param $mimes
     * @param string $disk
     * @return \Plank\Mediable\Media
     * @throws \Plank\Mediable\Exceptions\MediaUpload\ConfigurationException
     * @throws \Plank\Mediable\Exceptions\MediaUpload\FileExistsException
     * @throws \Plank\Mediable\Exceptions\MediaUpload\FileNotFoundException
     * @throws \Plank\Mediable\Exceptions\MediaUpload\FileNotSupportedException
     * @throws \Plank\Mediable\Exceptions\MediaUpload\FileSizeException
     * @throws \Plank\Mediable\Exceptions\MediaUpload\ForbiddenException
     * @return array
     */
    public function upload($file, $attributes, $mimes, $directory, $disk = 'public')
    {
        //Verifico si es una cadena
        if(is_string($file))
            //verifico si es una url, si es una url prosigo con la carga con MediaUploader
            //de lo contrario asumo que es un json value con los datos del model para crear
            // nuevo registro en la tabla medias
            if(!filter_var($file,FILTER_VALIDATE_URL)){
                //tengo JSON del fichero y creo model con campos fillables del json
                try{
                    $properties = json_decode($file);
                    $properties->attributes = $attributes;
                    $media = new Media();
                    $media->video_id = $properties->video_id;
                    $media->video_url = $properties->video_url;
                    $media->video_thumbnail = $properties->video_thumbnail;
                    $media->filename  = md5($properties->video_id);
                    $media->attributes = $attributes;
                    $media->size = $properties->size;
                    $media->mime_type = 'text/uri-list';
                    $media->aggregate_type = $properties->type;
                    $media->extension= $properties->extension;
                    $media->save();
                    return ['url'=>$media->refresh()];
                }
                catch(Exception $e){
                    throw new FileException(__('exceptions.file.json_file_incorrect'));
                }
            }

        $directory = 'uploads/' . $directory;

        $factory = app(SourceAdapterFactory::class);

        $adapater = $factory->create($file);

        $mime = $adapater-> mimeType();

        $filename = md5($adapater->filename());

        $media = MediaUploader::fromSource($adapater);

        $media = $media->useFilename($filename)->beforeSave(function (Media $model) use ($attributes) {
            $model->setAttribute('attributes', $attributes);
        });

        if (!empty($mimes))
            $media = $media->setAllowedMimeTypes($mimes);

        if ($this->hasMimeTypes($mime, 'image')) {
            $directory .= '/img';

            $media = $media->toDestination($disk, $directory . '/original');
            $media = $this->save($media);

            $images = ['original' => $media];

            $tmp = tempnam(sys_get_temp_dir(), 'image'); // store in a temporary location
            rename($tmp, $tmp = $tmp . ".{$media->extension}");

            $this->saveImageDimensions($filename, $tmp, $disk, $directory, $media, $attributes, $images);

            $medias = ['img' => $images];

        } elseif ($this->hasMimeTypes($mime, 'document')) {
            $directory .= '/doc';
            $media = $media->toDestination($disk, $directory);
            $media = $this->save($media);

            $medias = ['doc' => $media];
        } elseif ($this->hasMimeTypes($mime, 'video')) {
            $directory .= '/video';
            $media = $media->toDestination($disk, $directory);
            $media = $this->save($media);

            $medias = ['video' => $media];
        } elseif ($this->hasMimeTypes($mime, 'any')) {
            $directory .= '/misc';
            $media = $media->toDestination($disk, $directory);
            $media = $this->save($media);

            $medias = ['misc' => $media];
        } else
            throw new FileException(__('exceptions.file.invalid_mime_type',['ext'=>$mime]), 403);

        return $medias;

    }

    public function uploadMany(array $files, $disk = 'uploads', $directory = '/galeria')
    {
        // TODO: Implement uploadMany() method.
    }

    /**
     * @param $tmp
     * @param $disk
     * @param $directory
     * @param $media
     * @param $attributes
     * @param $medias
     * @return mixed
     * @throws \Plank\Mediable\Exceptions\MediaUpload\ConfigurationException
     * @throws \Plank\Mediable\Exceptions\MediaUpload\FileExistsException
     * @throws \Plank\Mediable\Exceptions\MediaUpload\FileNotFoundException
     * @throws \Plank\Mediable\Exceptions\MediaUpload\FileNotSupportedException
     * @throws \Plank\Mediable\Exceptions\MediaUpload\FileSizeException
     * @throws \Plank\Mediable\Exceptions\MediaUpload\ForbiddenException
     */
    private function saveImageDimensions($filename, $tmp, $disk, $directory, $media, $attributes, &$medias)
    {
        foreach (config('mediable.image_dimensions') as $type => $dimensions) {
            list($width, $height) = $dimensions;
            Image::make($media->getAbsolutePath())
                ->resize($width, $height)
                ->save($tmp);

            $processed = MediaUploader::fromSource($tmp)
                ->useFilename($filename)
                ->beforeSave(function (Media $model) use ($attributes) {
                    $model->setAttribute('attributes', $attributes);
                })
                ->toDestination($disk, $directory . '/' . $type);
            $medias [$type] = $this->save($processed);
        }

        return $medias;
    }

    private function hasMimeTypes($needle, $key)
    {
        switch ($key) {
            case 'image':
                $mimes = array_merge(
                    config('mediable.aggregate_types')[Media::TYPE_IMAGE]['mime_types'],
                    config('mediable.aggregate_types')[Media::TYPE_IMAGE_VECTOR]['mime_types']
                );
                break;
            case 'document':
                $mimes = array_merge(
                    config('mediable.aggregate_types')[Media::TYPE_DOCUMENT]['mime_types'],
                    config('mediable.aggregate_types')[Media::TYPE_PDF]['mime_types'],
                    config('mediable.aggregate_types')[Media::TYPE_SPREADSHEET]['mime_types'],
                    config('mediable.aggregate_types')[Media::TYPE_PRESENTATION]['mime_types']
                );
                break;
            case 'video':
                $mimes = config('mediable.aggregate_types')[Media::TYPE_VIDEO]['mime_types'];
                break;
            default:
                $mimes = array_merge(
                    config('mediable.aggregate_types')[Media::TYPE_AUDIO]['mime_types'],
                    config('mediable.aggregate_types')[Media::TYPE_ARCHIVE]['mime_types']
                );
                break;
        }
        return in_array($needle, $mimes);
    }

    private function save($media)
    {
        try {
            return $media->upload();
        } catch (Exception $e) {
            throw new FileException($e->getMessage());
        }
    }
}