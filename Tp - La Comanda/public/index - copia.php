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
require_once './middlewares/MWAccesos.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = AppFactory::create();
$app->setBasePath('/public');
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();//sin esto no toma params p el delete ni el put

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

//region Peticiones
$app->group('/empleados', function (RouteCollectorProxy $group) {
  $group->post('/login', \EmpleadoController::class . ':Login');
  $group->post('/alta', \EmpleadoController::class . ':CargarUno')->add(MWAccesos::class . ':soloSocio');//para MW se llama asi en x grupo o ruta se executa este mw

});

$app->group('/productos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \ProductoController::class . ':TraerTodos');
  $group->get('/{tipo}', \ProductoController::class . ':TraerTipo');
  $group->post('[/alta]', \ProductoController::class . ':CargarUno')->add(MWAccesos::class . ':soloSocio');
});

$app->group('/mesas', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MesaController::class . ':TraerTodos');
  $group->get('/{id}', \MesaController::class . ':TraerUno');
  $group->post('[/alta]', \MesaController::class . ':CargarUno')->add(MWAccesos::class . ':soloSocio'); 
});

$app->group('/pedidos', function (RouteCollectorProxy $group) {
  $group->get('/listarPendientes', \PedidoController::class . ':TraerTodos')->add(MWAccesos::class. ':soloSocio');
  $group->get('/pendientesCocina', \PedidoController::class . ':TraerUno');
  $group->get('/pendientesBartender', \PedidoController::class . ':TraerUno');
  $group->get('/pendientesCerveza', \PedidoController::class . ':TraerUno');
  $group->post('/agregar', \PedidoController::class . ':AgregarProducto')->add(MWAccesos::class . ':soloSocio_Mozo');
  $group->post('/alta', \PedidoController::class . ':CargarUno')->add(MWAccesos::class . ':soloSocio_Mozo');
});

// $app->group('/ventas', function (RouteCollectorProxy $group) {
//   $group->get('[/]', \PedidoController::class . ':TraerTodos');
//   $group->get('/{id}', \PedidoController::class . ':TraerUno');
//   $group->post('[/alta]', \PedidoController::class . ':CargarUno')->add(MWAccesos::class . ':soloSocio'); 
// });
//endRegion


// Run app
$app->run();

