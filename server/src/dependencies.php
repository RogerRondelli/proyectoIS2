<?php
// DIC configuration
use Psr\Container\ContainerInterface as Container;
use Slim\Http\Request;
use Slim\Http\Response;
$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    // $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path']));
    return $logger;
};

// monolog
$container['loggerResponse'] = function ($c) {
    $settings = $c->get('settings')['loggerResponse'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    // $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path']));
    return $logger;
};

// PDO database library
$container['db'] = function ($c) {
    $settings = $c->get('settings')['db'];
    $pdo = new PDO("mysql:host=" . $settings['host'] . ";dbname=" . $settings['dbname'].";charset=UTF8", $settings['user'], $settings['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

// Send Mail
$container['SendMail']  = function () {
    $myService = new SendMail();
    return $myService;
};

// Slim Framework application error handler / if it is set to false in setting
if($container->get('settings')['displayErrorDetails']){
    $container['errorHandler'] = function (Container $container) {
        $logger = $container->get('logger');
        $render = $container->get('renderer');
        return function(Request $request, Response $response, Throwable $exception) use ($logger,$render) {
            $error = "Error number [".$exception->getCode()."] ".$exception->getMessage()." on line ".$exception->getLine()." in file ".$exception->getFile()."";
            $logger->error($error);

            // return $render->render($response, 'error.phtml', [
            //     "error" => $error,
            //     "msg" => "Error en el Servidor"
            // ]);

            return $response
                        ->withJson(array("status" => false, "message" => $error, "error_code" => $exception->getCode(), "data" => array( "msg" => "Error en el Servidor")));  
            // return $response->withStatus(500) 
            //     ->withHeader('Content-Type', 'text/html')
            //     ->write('Algo salió mal!');
            
        };
    };
    
    $container['phpErrorHandler'] = function (Container $container) {
        return $container->get('errorHandler');
    };
}

