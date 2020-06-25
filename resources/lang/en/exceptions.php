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
        'default'=>'Upps, Something went wrong uploading your files.',
        'invalid_mime_type'=>'The file extension ":EXT" is forbidden for this server.',
        'too_large'=>'Some files size are greater than permitted size to upload.',
        'json_file_incorrect'=>'The structure of json file is not correct.',
    ],
    'domain'=>[
        'default'=>'Something get wrong with your data.',
    ],
    'repository'=>[
        'default'=>'Something get wrong getting your data.',
        'method_doesnt_exist'=>'Repository method ":method" doesn\'t exists'
    ],
    'transaction'=>[
        'default'=>'Something get wrong saving your data.',
    ],
    'validation'=>[
        'default'=>'Your data is invalid.',
    ],
    'dependencies'=>[
        'parameters_trait'=>'It is necessary include Parameters Trait to access ":function" functionality.',
        'media_manager_trait'=>'It is necessary include MediaManager Trait in model to access ":function" functionality.',
    ]
];
