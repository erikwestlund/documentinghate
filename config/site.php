<?php

return [
    'title' => 'Remembering Love',
    'description' => 'Remembering Love is a crowd-sourced repository of acts of love in the United States',
    'tagline' => 'A crowd-sourced listing of acts of love in the US',
    'per_page' => 5,
    'date_format' => 'F d, Y',
    'short_description_length' => 300,
    'uploads' => [
        'storage' => 's3',
        'webpath' => 'https://s3.amazonaws.com/rememberinglove',
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
