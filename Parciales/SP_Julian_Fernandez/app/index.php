<?php
// Error Handling
date_default_timezone_set("America/Argentina/Buenos_Aires");
error_reporting(-1);
error_reporting(E_ALL ^ E_DEPRECATED); // no mostrar codigo deprecado
ini_set('display_errors', 1);

// // require_once 'vendor/autoload.php';
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require_once __DIR__ . '/../vendor/autoload.php';
require_once './db/AccesoDatos.php';
require_once './controllers/UsuarioController.php';
require_once './controllers/ClienteController.php';
require_once './controllers/ReservaController.php';
require_once './controllers/AuthController.php';
require_once './controllers/CSVController.php';
require_once './controllers/JSONController.php';

require_once './middlewares/CamposClienteMW.php';
require_once './middlewares/CamposReservaMW.php';
require_once './middlewares/AuthMiddleware.php';
require_once './middlewares/LogMiddleware.php';
require_once './middlewares/LogTransaccionMiddleware.php';

$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

$app->get('[/]', function (Request $request, Response $response) {
    $response->getBody()->write(json_encode(['response' => 'Slim project']));
    return $response;
});

$app->group('/login', function (RouteCollectorProxy $group) {
    $group->post('[/]', \AuthController::class . ':GenerarToken')
        // ->add(new LogTransaccionMiddleware())
        ->add(\AuthMiddleware::class . ':validarLogin');
})->add(new LogMiddleware());

$app->group('/clientes', function (RouteCollectorProxy $group) {
    $traerTodos = new AuthMiddleware();
    $traerUno = new AuthMiddleware();
    $cargarUno = new AuthMiddleware();
    $modificarUno = new AuthMiddleware();
    $borrarUno = new AuthMiddleware();

    $traerTodos->setTiposPermitidos(['recepcionista', 'cliente']);
    $traerUno->setTiposPermitidos(['recepcionista', 'cliente']);
    $cargarUno->setTiposPermitidos(['gerente']);
    $modificarUno->setTiposPermitidos(['gerente']);
    $borrarUno->setTiposPermitidos(['gerente']);

    $group->get('[/]', \ClienteController::class . ':TraerTodos')
        ->add($traerTodos)
        ->add(\AuthMiddleware::class . ':verificarToken');

    $group->get('/{cliente}', \ClienteController::class . ':TraerUno')
        ->add($traerUno)
        ->add(\AuthMiddleware::class . ':verificarToken');

    $group->post('[/]', \ClienteController::class . ':CargarUno')
        // ->add(new LogTransaccionMiddleware())
        ->add(new CamposClienteMW())
        ->add($cargarUno)
        // ->add(\AuthMiddleware::class . ':obtenerDataToken')
        ->add(\AuthMiddleware::class . ':verificarToken');

    $group->put('/{cliente}', \ClienteController::class . ':ModificarUno')
        // ->add(new LogTransaccionMiddleware())
        ->add($modificarUno)
        // ->add(\AuthMiddleware::class . ':obtenerDataToken')
        ->add(\AuthMiddleware::class . ':verificarToken');

    $group->delete('/{cliente}', \ClienteController::class . ':BorrarUno')
        // ->add(new LogTransaccionMiddleware())
        ->add($borrarUno)
        // ->add(\AuthMiddleware::class . ':obtenerDataToken')
        ->add(\AuthMiddleware::class . ':verificarToken');
})->add(new LogMiddleware());

$app->group('/reservas', function (RouteCollectorProxy $group) {
    $traerTodos = new AuthMiddleware();
    $traerUno = new AuthMiddleware();
    $cargarUno = new AuthMiddleware();
    $modificarUno = new AuthMiddleware();
    $borrarUno = new AuthMiddleware();
    $cancelarUno = new AuthMiddleware();
    $ajustarUno = new AuthMiddleware();

    $traerTodos->setTiposPermitidos(['recepcionista', 'cliente']);
    $traerUno->setTiposPermitidos(['recepcionista', 'cliente']);
    $cargarUno->setTiposPermitidos(['recepcionista', 'cliente']);
    $modificarUno->setTiposPermitidos(['recepcionista', 'cliente']);
    $borrarUno->setTiposPermitidos(['recepcionista', 'cliente']);
    $cancelarUno->setTiposPermitidos(['recepcionista', 'cliente']);
    $ajustarUno->setTiposPermitidos(['recepcionista', 'cliente']);

    $group->get('[/]', \ReservaController::class . ':TraerTodos')
        ->add($traerTodos)
        ->add(\AuthMiddleware::class . ':verificarToken');
        // ->add(new LogMiddleware());

    $group->get('/{reserva}', \ReservaController::class . ':TraerUno')
        ->add($traerUno)
        ->add(\AuthMiddleware::class . ':verificarToken');

    $group->post('[/]', \ReservaController::class . ':CargarUno')
        // ->add(new LogTransaccionMiddleware())
        ->add(new CamposReservaMW())
        ->add($cargarUno)
        // ->add(\AuthMiddleware::class . ':obtenerDataToken')
        ->add(\AuthMiddleware::class . ':verificarToken');

    $group->put('/{reserva}', \ReservaController::class . ':ModificarUno')
        // ->add(new LogTransaccionMiddleware())
        ->add($modificarUno)
        // ->add(\AuthMiddleware::class . ':obtenerDataToken')
        ->add(\AuthMiddleware::class . ':verificarToken');

    $group->delete('/{reserva}', \ReservaController::class . ':BorrarUno')
        // ->add(new LogTransaccionMiddleware())
        ->add($borrarUno)
        // ->add(\AuthMiddleware::class . ':obtenerDataToken')
        ->add(\AuthMiddleware::class . ':verificarToken');

    $group->post('/{reserva}/cancelar', \ReservaController::class . ':CancelarUno')
        // ->add(new LogTransaccionMiddleware())
        ->add($cancelarUno)
        // ->add(\AuthMiddleware::class . ':obtenerDataToken')
        ->add(\AuthMiddleware::class . ':verificarToken');

    $group->post('/{reserva}/ajuste', \ReservaController::class . ':AjustarUno')
        ->add(new LogTransaccionMiddleware())
        ->add($ajustarUno)
        ->add(\AuthMiddleware::class . ':obtenerDataToken')
        ->add(\AuthMiddleware::class . ':verificarToken');
})->add(new LogMiddleware());

$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->post('[/]', \UsuarioController::class . ':CargarUno');
});

$app->group('/csv', function (RouteCollectorProxy $group) {
    $group->get('/descargar', \CSVController::class . ':DescargarEntidad');
    $group->post('/cargar', \CSVController::class . ':CargarEntidad');
})->add(new LogMiddleware());

$app->group('/json', function (RouteCollectorProxy $group) {
    $group->get('/descargar', \JSONController::class . ':DescargarEntidad');
    $group->post('/cargar', \JSONController::class . ':CargarEntidad');
})->add(new LogMiddleware());

$app->run();
?>