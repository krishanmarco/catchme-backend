

<?php

return [
    'propel' => [
        'database' => [
            'connections' => [
                'catch_me' => [
                    'adapter'    => 'mysql',
                    'classname'  => 'Propel\Runtime\Connection\ConnectionWrapper',
                    'dsn'        => 'mysql:host=localhost;dbname=krisaxcv_catchme-v2',
                    'user'       => 'krisaxcv_catchme-v2',
                    'password'   => 'w.kc5M!Q&J}f',
                    'attributes' => []
                ]
            ]
        ],
        'runtime' => [
            'defaultConnection' => 'catch_me',
            'connections' => ['catch_me']
        ],
        'generator' => [
            'defaultConnection' => 'catch_me',
            'connections' => ['catch_me']
        ]
    ]
];

