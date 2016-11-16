<?php

return [
    'title' => 'Documenting Hate',
    'entries_per_page' => 20,
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
