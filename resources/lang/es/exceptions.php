<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Exceptions Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'file'=>[
        'default'=>'Upps, Algo salió mal al subir sus archivos.',
        'invalid_mime_type'=>'La extensión de archivo ": ext" está prohibida para este servidor.',
        'too_large'=>'Algunos tamaños de archivos son mayores que el tamaño permitido para cargar.',
    ],
    'domain'=>[
        'default'=>'Algo salió mal con tus datos.',
    ],
    'repository'=>[
        'default'=>'Algo salió mal al obtener tus datos.',
    ],
    'transaction'=>[
        'default'=>'Algo salió mal al guardar tus datos.',
    ],
    'validation'=>[
        'default'=>'Los datos proporcionados no son válidos.',
    ]
];
