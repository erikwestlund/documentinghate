<?php

return [
    'title' => 'Documenting Hate',
    'description' => 'Documenting Hate is a crowd-sourced repository of incidents of hate in the United States',
    'tagline' => 'A crowd-sourced listing of incidents of hate in the US',
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
    ],
    'mapbox' => [
        'project' => 'edbwestlund.25eem9o7',
        'token' => 'pk.eyJ1IjoiZWRid2VzdGx1bmQiLCJhIjoiY2l2cGRiNWFtMDFlcjJ6cDUxMjIyY2t6eiJ9.ZPA1uCdn-rkxJmK-0obPwQ'
    ],
];
