<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        'determineRouteBeforeAppMiddleware' => true,
        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app_'.date('m/d/Y').'.log',
            // 'level' => \Monolog\Logger::ERROR,
        ],

        // Monolog settings logger response
        'loggerResponse' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/appResponse_'.date('Y-m-d').'_.log',
            // 'level' => \Monolog\Logger::ERROR,
        ],

        // database connection details         
        "db" => [            
             "host" => "localhost",             
             "dbname" => DB_BASE,             
             "user" => DB_USER,            
             "pass" => DB_PSW   
        ],

        // jwt settings
        "jwt" => [
            'secret' => 'mbokaja123'
        ]
    ],
];
