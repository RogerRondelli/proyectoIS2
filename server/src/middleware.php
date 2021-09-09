<?php
use Slim\Http\Request;
use Slim\Http\Response;

// Application middleware
$container = $app->getContainer();


// // e.g: $app->add(new \Slim\Csrf\Guard); token 
$app->add(new \Tuupola\Middleware\JwtAuthentication([
    "path" =>["/api", "/app"], /* or ["/api", "/admin"] */
    "ignore" => ["/api","/api/users/signin","/api/users/register"], 
    // "ignore" => [""], 
    "logger" => $container["logger"],
    "attribute" => "token",
    "secure" => false,
    "relaxed" => ["localhost", "192.168.0.16", "192.168.0.112"],
    "secret" => "mbokaja123",
    "algorithm" => ["HS256"],
    "error" => function ($response, $arguments) {
        $data["status"] = false;
        $data["error_code"] = 1030;
        $data["message"] =  $arguments;
        $data["data"] = ["msg" => "Acceso Denegado"];
        return $response
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    },
    "callback" => function ($request, $response, $arguments) {
        return JWTAuth::verifyToken(str_replace('Bearer ', '', $request->getServerParams()['Token']));
    }
]));

// Middleware to handle minor errors
$app->add(function (Request $request, Response $response, $next) {
    $logger = $this->logger;
    $settings = $this->get('settings');
    // error handler function
    $myHandlerForMinorErrors = function ($errno, $errstr, $errfile, $errline) use ($response, $logger,$settings) {
        switch ($errno) {
            case E_USER_ERROR:
            case E_ERROR:
                $logger->error("Error number [$errno] $errstr on line $errline in file $errfile");
                break;
            case E_USER_WARNING:
            case E_WARNING:
                $logger->warning("Error number [$errno] $errstr on line $errline in file $errfile");
                break;
            case E_USER_NOTICE:
            case E_NOTICE:
                $logger->notice("Error number [$errno] $errstr on line $errline in file $errfile");
                break;
            default:
                $logger->notice("Error number [$errno] $errstr on line $errline in file $errfile");
                break;
        }
        // Optional: Write error to response
        // $response->withJson(array("response" => "0", "error" => "Error: [$errno] $errstr<br>\n" ,"data" => array("msg" => "Error Interno")));
        // $response = $response->getBody()->write("Error: [$errno] $errstr<br>\n");
        // Don't execute PHP internal error handler
        if( $settings['displayErrorDetails'] ) return false;
        return true;
    };

    // Set custom php error handler for minor errors
    set_error_handler($myHandlerForMinorErrors, E_NOTICE | E_STRICT);
    return $next($request, $response);
});

//para probar algo
$app->add(function ($req, $res, $next) {
    ##### Ejemplo para enviar correo por el servidor
        // $title = '📌 Sistema';
        // $from = ['developer@proinso.sa.com' => 'Sistema de Gestión'];
        // $to = ['saldivarcristian@gmail.com', 'echinfer@gmail.com' => 'echin','comodin.taller.servicios@gmail.com','saldivarcristian@hotmail.com'];
        // $body = '.!.';
        // $this->SendMail->Send($title,$from,$to,$body);
    ##### Fin 

    // ServidorEmail::ValidacionFotoUsuario(date('Y-m-d H:i:s'), 3582196);

    return $next($req, $res); 
});

$app->add(new Tuupola\Middleware\CorsMiddleware([
    "origin" => ["*"],
    "logger" =>  $container["logger"],
    "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE"],
    "headers.allow" => ["Content-Type", "Accept", "Origin", "Authorization", "version","id","key", "Token"],
    "headers.expose" => [],
    "credentials" => false,
    "cache" => 0,
    "error" => function ($request, $response, $arguments) {
        $data["status"] = "error";
        $data["message"] = $arguments["message"];
        return $response
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
]));