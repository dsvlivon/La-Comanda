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
  $group->post('/descargaCsv', \EmpleadoController::class . ':GuardarCSV')->add(MWAccesos::class . ':soloSocio');
  $group->post('/cargaCsv', \EmpleadoController::class . ':CargarCSV')->add(MWAccesos::class . ':soloSocio');
});

$app->group('/productos', function (RouteCollectorProxy $group) {
  $group->post('[/alta]', \ProductoController::class . ':CargarUno')->add(MWAccesos::class . ':soloSocio');
});

$app->group('/mesas', function (RouteCollectorProxy $group) {
  $group->post('[/alta]', \MesaController::class . ':CargarUno')->add(MWAccesos::class . ':soloSocio'); 
});

$app->group('/clientes', function (RouteCollectorProxy $group){
  $group->get('/checkPedido/{codigoPedido}', \PedidoController::class. ':TraerPedido');
  //$group->get('/TraerPorId/{id}', \CriptoController::class. ':TraerUno'
});

$app->group('/pedidos', function (RouteCollectorProxy $group) {
  $group->get('/listarPendientes', \PedidoController::class . ':TraerTodos')->add(MWAccesos::class. ':soloSocio');

  $group->get('/pendientesCocina', \PedidoController::class . ':TraerPendientesCocina')->add(MWAccesos::class. ':soloCocinero');
  $group->get('/pendientesBartender', \PedidoController::class . ':TraerPendientesBartender')->add(MWAccesos::class. ':soloBartender');

  $group->put('/actualizarPedidoCocina', \PedidoController::class. ':ActualizarPedido')->add(MWAccesos::class. ':soloCocinero');
  $group->put('/actualizarPedidoCandyBar', \PedidoController::class. ':ActualizarPedido')->add(MWAccesos::class. ':soloCocinero');
  
  $group->put('/actualizarPedidoCervezas', \PedidoController::class. ':ActualizarPedido')->add(MWAccesos::class. ':soloBartender');
  $group->put('/actualizarPedidoTragos', \PedidoController::class. ':ActualizarPedido')->add(MWAccesos::class. ':soloBartender');

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

