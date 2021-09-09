<?php
require '../vendor/autoload.php';
require '../../admin/structure/name.php';

// Set up tools
require __DIR__ . '/../src/tools.php';

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register routes
require __DIR__.'/../src/routes.php';

$app->run();
?>