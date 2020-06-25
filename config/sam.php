<?php 
 return [
    'models' => [
        'user' => 'App\\Models\\User',
    ],
    'tables' => [
        'users' => 'users',
        'publishers' => 'publishers',
    ],
    'publishers' => [
        'gallito' => [
            'classpath' => 'Sitios\\Sam\\Publishers\\Gallito',
            'auth' => [
            ],
            'endpoints' => [
                'protocol' => 'https',
                'users' => [
                ],
                'vehicles' => [
                ],
                'services' => [
                ],
            ],
        ],
        'mercadolibre' => [
            'classpath' => 'Sitios\\Sam\\Publishers\\Meli',
            'auth' => [
                'MLA' => 'https://auth.mercadolibre.com.ar',
                'MLB' => 'https://auth.mercadolivre.com.br',
                'MCO' => 'https://auth.mercadolibre.com.co',
                'MCR' => 'https://auth.mercadolibre.com.cr',
                'MEC' => 'https://auth.mercadolibre.com.ec',
                'MLC' => 'https://auth.mercadolibre.cl',
                'MLM' => 'https://auth.mercadolibre.com.mx',
                'MLU' => 'https://auth.mercadolibre.com.uy',
                'MLV' => 'https://auth.mercadolibre.com.ve',
                'MPA' => 'https://auth.mercadolibre.com.pa',
                'MPE' => 'https://auth.mercadolibre.com.pe',
                'MPT' => 'https://auth.mercadolibre.com.pt',
                'MRD' => 'https://auth.mercadolibre.com.do',
            ],
            'endpoints' => [
                'api' => 'https://api.mercadolibre.com',
                'users' => [
                ],
                'listing_types' => [
                    'list' => 'sites/{country}/listing_types',
                    'details' => 'sites/{country}/listing_types/{id}',
                    'exposures' => 'sites/{country}/listing_exposures',
                ],
                'currencies' => [
                    'list' => 'currencies',
                    'details' => 'currencies/{id}',
                    'conversion' => '/currency_conversions/search?from={from}&to={to}',
                ],
                'locations' => [
                    'list' => 'classified_locations/countries',
                    'country' => 'classified_locations/{country}',
                    'state' => 'classified_locations/states/{state}',
                    'city' => 'classified_locations/cities/{city}',
                ],
                'categories' => [
                    'list' => 'sites/{country}/categories',
                    'details' => 'categories/{id}',
                    'attributes' => 'categories/{id}/attributes',
                    'promotions' => 'categories/{id}/classifieds_promotion_packs',
                ],
                'items' => [
                    'details' => [
                        'details' => 'items/{id}',
                        'create' => 'items',
                        'update' => 'items/{id}',
                        'validate' => 'items/validate',
                        'upgrades_by_listing' => 'items/{id}/available_upgrades',
                        'sell' => 'items/{id}/relist',
                        'pictures' => [
                            'list' => 'pictures/{id}',
                            'delete' => 'pictures/{id}',
                        ],
                        'description' => [
                            'details' => 'items/{id}/description',
                            'update' => 'items/{id}/description',
                        ],
                        'search' => [
                            'in_site' => 'sites/{country}/search?q={query}&access_token={token}',
                            'all' => 'sites/{country}/searchUrl?q={query}&access_token={token}',
                            'by_category' => 'sites/{country}/hot_items/search?category={id}&access_token={token}',
                        ],
                    ],
                ],
            ],
        ],
    ],
];