<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';
require_once './controllers/EmpleadoController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';
require_once './db/AccesoDatos.php';
require_once './middlewares/AutentificadorJWT.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = AppFactory::create();
$app->setBasePath('/public');
$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Bienvenidos a la Comanda!");
    return $response;
});


//region Peticiones
$app->group('/empleados', function (RouteCollectorProxy $group) {
    $group->get('[/]', \EmpleadoController::class . ':TraerTodos');
    $group->get('/{id}', \EmpleadoController::class . ':TraerUno');
  
    $group->post('/LogIn', \EmpleadoController::class . ':LogIn');
    $group->post('[/alta]', \EmpleadoController::class . ':CargarUno')->add(AutentificadorJWT::class . ':obtenerData');
});

$app->group('/productos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \ProductoController::class . ':TraerTodos');
  // $group->get('/{id}', \ProductoController::class . ':TraerUno');
  $group->get('/{tipo}', \ProductoController::class . ':TraerTipo');
  
  $group->post('[/alta]', \ProductoController::class . ':CargarUno');
  // })->add(\UsuarioController::class . ':TraerUno'); 
});

$app->group('/mesas', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MesaController::class . ':TraerTodos');
  $group->get('/{id}', \MesaController::class . ':TraerUno');

  $group->post('[/alta]', \MesaController::class . ':CargarUno'); 
});

$app->group('/pedidos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PedidoController::class . ':TraerTodos');
  $group->get('/{id}', \PedidoController::class . ':TraerUno');

  $group->post('[/alta]', \PedidoController::class . ':CargarUno'); 
});
//endRegion

$app->group('/credenciales', function (RouteCollectorProxy $group) {
  $group->get('[/]', \CredencialesController::class . ':TraerTodos'); //
  
})->add(\ValidacionesMW::class . ':ValidarUser');//para MW se llama asi 
//en x grupo o ruta se executa este mw

// JWT test routes
$app->group('/jwt', function (RouteCollectorProxy $group) {

  $group->post('/crearToken', function (Request $request, Response $response) {    
  //   $parametros = $request->getParsedBody();  
  //   $id = $parametros['id'];
  //   $clave = $parametros['clave'];

  //   $e = new Empleado();
  //   $e->id = $id;
  //   $e->clave = $clave;

  //   if(Empleado::ValidarEmpleado($e)){ //deberia volver el Emp completo
      
  //     $datos = array('empleado' => $e->nombre, 'tipo' => $e->tipo);
  //     $token = AutentificadorJWT::CrearToken($datos);
  //     $payload = json_encode(array('jwt' => $token));
  //     $response->getBody()->write($payload);
  //   } 
  //   else {
  //     echo "NO FUE POSIBLE";
  //   }
  //   return $response
  //   ->withHeader('Content-Type', 'application/json');
    
  });

  $group->get('/devolverPayLoad', function (Request $request, Response $response) {
    $header = $request->getHeaderLine('Authorization');
    $token = trim(explode("Bearer", $header)[1]);

    try {
      $payload = json_encode(array('payload' => AutentificadorJWT::ObtenerPayLoad($token)));
    } catch (Exception $e) {
      $payload = json_encode(array('error' => $e->getMessage()));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  });

  $group->get('/devolverDatos', function (Request $request, Response $response) {
    $header = $request->getHeaderLine('Authorization');
    $token = trim(explode("Bearer", $header)[1]);

    try {
      $payload = json_encode(array('datos' => AutentificadorJWT::ObtenerData($token)));
    } catch (Exception $e) {
      $payload = json_encode(array('error' => $e->getMessage()));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  });

  $group->get('/verificarToken', function (Request $request, Response $response) {
    $header = $request->getHeaderLine('Authorization');
    $token = trim(explode("Bearer", $header)[1]);
    $esValido = false;

    try {
      AutentificadorJWT::verificarToken($token);
      $esValido = true;
    } catch (Exception $e) {
      $payload = json_encode(array('error' => $e->getMessage()));
    }

    if ($esValido) {
      $payload = json_encode(array('valid' => $esValido));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  });
});

// Run app
$app->run();

