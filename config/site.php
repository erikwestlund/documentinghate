<?php

return [
    'title' => 'Documenting Hate',
    'per_page' => 5,
    'date_format' => 'F d, Y',
    'short_description_length' => 300,
    'uploads' => [
        'storage' => 's3',
        'webpath' => 'https://s3.amazonaws.com/documentinghate',
    ],
    'photos' => [
        'directory' => 'photos/',
        'thumbnails' => [
            'quality' => 90,
            'width' => 300
        ]
        
    ],
    'admin' => [
        'per_page' => 25
    ] 
];
