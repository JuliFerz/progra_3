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
require_once './controllers/ClienteController.php';
require_once './controllers/ReservaController.php';
require_once './controllers/AuthController.php';
require_once './controllers/CSVController.php';
require_once './controllers/JSONController.php';

require_once './middlewares/CamposClienteMW.php';
require_once './middlewares/CamposReservaMW.php';
require_once './middlewares/AuthMiddleware.php';

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
        ->add(\AuthMiddleware::class . ':validarLogin');
});

$app->group('/clientes', function (RouteCollectorProxy $group) {
    // $traerTodos = new AuthMiddleware();
    // $traerUno = new AuthMiddleware();
    // $cargarUno = new AuthMiddleware();
    // $modificarUno = new AuthMiddleware();
    // $borrarUno = new AuthMiddleware();

    // $traerTodos->setTiposPermitidos(['corpo', 'indi']);
    // $traerUno->setTiposPermitidos(['admin', 'socio']);
    // $cargarUno->setTiposPermitidos(['admin', 'socio']);
    // $modificarUno->setTiposPermitidos(['admin', 'socio']);
    // $borrarUno->setTiposPermitidos(['admin', 'socio']);

    $group->get('[/]', \ClienteController::class . ':TraerTodos')
        // ->add($traerTodos)
        ->add(\AuthMiddleware::class . ':verificarToken');

    $group->get('/{cliente}', \ClienteController::class . ':TraerUno')
    //     ->add($traerUno)
        ->add(\AuthMiddleware::class . ':verificarToken');

    $group->post('[/]', \ClienteController::class . ':CargarUno')
        ->add(new CamposClienteMW())
    //     ->add($cargarUno)
        ->add(\AuthMiddleware::class . ':verificarToken');

    $group->put('/{cliente}', \ClienteController::class . ':ModificarUno')
    //     ->add($modificarUno)
        ->add(\AuthMiddleware::class . ':verificarToken');

    $group->delete('/{cliente}', \ClienteController::class . ':BorrarUno')
    //     ->add($borrarUno)
        ->add(\AuthMiddleware::class . ':verificarToken');
});

$app->group('/reservas', function (RouteCollectorProxy $group) {
    // $traerTodos = new AuthMiddleware();
    // $traerUno = new AuthMiddleware();
    // $cargarUno = new AuthMiddleware();
    // $modificarUno = new AuthMiddleware();
    // $borrarUno = new AuthMiddleware();

    // $traerTodos->setTiposPermitidos(['admin', 'socio']);
    // $traerUno->setTiposPermitidos(['admin', 'socio']);
    // $cargarUno->setTiposPermitidos(['admin', 'socio']);
    // $modificarUno->setTiposPermitidos(['admin', 'socio']);
    // $borrarUno->setTiposPermitidos(['admin', 'socio']);

    $group->get('[/]', \ReservaController::class . ':TraerTodos')
        // ->add($traerTodos)
        ->add(\AuthMiddleware::class . ':verificarToken');

    $group->get('/{reserva}', \ReservaController::class . ':TraerUno')
    //     ->add($traerUno)
        ->add(\AuthMiddleware::class . ':verificarToken');

    $group->post('[/]', \ReservaController::class . ':CargarUno')
        ->add(new CamposReservaMW())
    //     ->add($cargarUno)
        ->add(\AuthMiddleware::class . ':verificarToken');

    $group->put('/{reserva}', \ReservaController::class . ':ModificarUno')
    //     ->add($modificarUno)
        ->add(\AuthMiddleware::class . ':verificarToken');

    $group->delete('/{reserva}', \ReservaController::class . ':BorrarUno')
    //     ->add($borrarUno)
        ->add(\AuthMiddleware::class . ':verificarToken');

    $group->post('/{reserva}/cancelar', \ReservaController::class . ':CancelarUno')
    //     ->add($modificarUno)
        ->add(\AuthMiddleware::class . ':verificarToken');

    $group->post('/{reserva}/ajuste', \ReservaController::class . ':AjustarUno')
    //     ->add($modificarUno)
        ->add(\AuthMiddleware::class . ':verificarToken');
});

$app->group('/csv', function (RouteCollectorProxy $group) {
    $group->get('/descargar', \CSVController::class . ':DescargarEntidad');
    $group->post('/cargar', \CSVController::class . ':CargarEntidad');
});

$app->group('/json', function (RouteCollectorProxy $group) {
    $group->get('/descargar', \JSONController::class . ':DescargarEntidad');
    $group->post('/cargar', \JSONController::class . ':CargarEntidad');
});

$app->run();
?>