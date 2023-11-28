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
// require_once './controllers/UsuarioController.php';
// require_once './controllers/PedidoController.php';
// require_once './controllers/ProductoController.php';
// require_once './controllers/SectorController.php';
// require_once './controllers/MesaController.php';
// require_once './controllers/EncuestaController.php';
// require_once './controllers/AuthController.php';
// require_once './controllers/CSVController.php';

require_once './middlewares/CamposClienteMW.php';
require_once './middlewares/CamposReservaMW.php';
// require_once './middlewares/AuthMiddleware.php';

$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

$app->get('[/]', function (Request $request, Response $response) {
    $response->getBody()->write(json_encode(['response' => 'Slim project']));
    return $response;
});

// $app->group('/login', function (RouteCollectorProxy $group) {
//     $group->post('[/]', \AuthController::class . ':GenerarToken')
//         ->add(\AuthMiddleware::class . ':validarLogin');
// });

$app->group('/clientes', function (RouteCollectorProxy $group) {
    // $traerTodos = new AuthMiddleware();
    // $traerUno = new AuthMiddleware();
    // $cargarUno = new AuthMiddleware();
    // $modificarUno = new AuthMiddleware();
    // $borrarUno = new AuthMiddleware();

    // $traerTodos->setSectoresPermitidos(['admin', 'socio']);
    // $traerUno->setSectoresPermitidos(['admin', 'socio']);
    // $cargarUno->setSectoresPermitidos(['admin', 'socio']);
    // $modificarUno->setSectoresPermitidos(['admin', 'socio']);
    // $borrarUno->setSectoresPermitidos(['admin', 'socio']);

    $group->get('[/]', \ClienteController::class . ':TraerTodos');
        // ->add($traerTodos)
        // ->add(\AuthMiddleware::class . ':verificarToken');

    $group->get('/{cliente}', \ClienteController::class . ':TraerUno');
    //     ->add($traerUno)
    //     ->add(\AuthMiddleware::class . ':verificarToken');

    $group->post('[/]', \ClienteController::class . ':CargarUno')
        ->add(new CamposClienteMW());
    //     ->add($cargarUno)
    //     ->add(\AuthMiddleware::class . ':verificarToken');

    $group->put('/{cliente}', \ClienteController::class . ':ModificarUno');
    //     ->add($modificarUno)
    //     ->add(\AuthMiddleware::class . ':verificarToken');

    $group->delete('/{cliente}', \ClienteController::class . ':BorrarUno');
    //     ->add($borrarUno)
    //     ->add(\AuthMiddleware::class . ':verificarToken');
});

$app->group('/reservas', function (RouteCollectorProxy $group) {
    // $traerTodos = new AuthMiddleware();
    // $traerUno = new AuthMiddleware();
    // $cargarUno = new AuthMiddleware();
    // $modificarUno = new AuthMiddleware();
    // $borrarUno = new AuthMiddleware();

    // $traerTodos->setSectoresPermitidos(['admin', 'socio']);
    // $traerUno->setSectoresPermitidos(['admin', 'socio']);
    // $cargarUno->setSectoresPermitidos(['admin', 'socio']);
    // $modificarUno->setSectoresPermitidos(['admin', 'socio']);
    // $borrarUno->setSectoresPermitidos(['admin', 'socio']);

    $group->get('[/]', \ReservaController::class . ':TraerTodos');
        // ->add($traerTodos)
        // ->add(\AuthMiddleware::class . ':verificarToken');

    $group->get('/{reserva}', \ReservaController::class . ':TraerUno');
    //     ->add($traerUno)
    //     ->add(\AuthMiddleware::class . ':verificarToken');

    $group->post('[/]', \ReservaController::class . ':CargarUno')
        ->add(new CamposReservaMW());
    //     ->add($cargarUno)
    //     ->add(\AuthMiddleware::class . ':verificarToken');

    $group->put('/{reserva}', \ReservaController::class . ':ModificarUno');
    //     ->add($modificarUno)
    //     ->add(\AuthMiddleware::class . ':verificarToken');

    $group->delete('/{reserva}', \ReservaController::class . ':BorrarUno');
    //     ->add($borrarUno)
    //     ->add(\AuthMiddleware::class . ':verificarToken');

    $group->post('/{reserva}/cancelar', \ReservaController::class . ':CancelarUno');
    //     ->add($modificarUno)
    //     ->add(\AuthMiddleware::class . ':verificarToken');

    $group->post('/{reserva}/ajuste', \ReservaController::class . ':AjustarUno');
    //     ->add($modificarUno)
    //     ->add(\AuthMiddleware::class . ':verificarToken');
});
// $app->group('/usuarios', function (RouteCollectorProxy $group) {
//     $traerTodos = new AuthMiddleware();
//     $traerUno = new AuthMiddleware();
//     $cargarUno = new AuthMiddleware();
//     $modificarUno = new AuthMiddleware();
//     $borrarUno = new AuthMiddleware();

//     $traerTodos->setSectoresPermitidos(['admin', 'socio']);
//     $traerUno->setSectoresPermitidos(['admin', 'socio']);
//     $cargarUno->setSectoresPermitidos(['admin', 'socio']);
//     $modificarUno->setSectoresPermitidos(['admin', 'socio']);
//     $borrarUno->setSectoresPermitidos(['admin', 'socio']);

//     $group->get('[/]', \UsuarioController::class . ':TraerTodos')
//         ->add($traerTodos)
//         ->add(\AuthMiddleware::class . ':verificarToken');

//     $group->get('/{usuario}', \UsuarioController::class . ':TraerUno')
//         ->add($traerUno)
//         ->add(\AuthMiddleware::class . ':verificarToken');

//     $group->post('[/]', \UsuarioController::class . ':CargarUno')
//         ->add(new FieldsMiddleware())
//         ->add($cargarUno)
//         ->add(\AuthMiddleware::class . ':verificarToken');

//     $group->put('/{usuario}', \UsuarioController::class . ':ModificarUno')
//         ->add($modificarUno)
//         ->add(\AuthMiddleware::class . ':verificarToken');

//     $group->delete('/{usuario}', \UsuarioController::class . ':BorrarUno')
//         ->add($borrarUno)
//         ->add(\AuthMiddleware::class . ':verificarToken');
// });

// $app->group('/pedidos', function (RouteCollectorProxy $group) {
//     $traerDisponibles = new AuthMiddleware();
//     $traerTodos = new AuthMiddleware();
//     $traerUno = new AuthMiddleware();
//     $cargarUno = new AuthMiddleware();
//     $modificarUno = new AuthMiddleware();
//     $borrarUno = new AuthMiddleware();
//     $tomarFoto = new AuthMiddleware();
//     $prepararPedido = new AuthMiddleware();
//     $completarPedido = new AuthMiddleware();
//     $servirPedido = new AuthMiddleware();
//     $cobrarPedido = new AuthMiddleware();
//     $cerrarPedido = new AuthMiddleware();

//     $traerDisponibles->setSectoresPermitidos(
//         ['admin', 'socio', 'cocinero', 'bartender', 'cervecero', 'mozo']);
//     $traerTodos->setSectoresPermitidos(
//         ['admin', 'socio', 'cocinero', 'bartender', 'cervecero', 'mozo']);
//     $traerUno->setSectoresPermitidos(
//         ['admin', 'socio', 'cocinero', 'bartender', 'cervecero', 'mozo']);
//     $cargarUno->setSectoresPermitidos(['admin', 'socio', 'mozo']);
//     $modificarUno->setSectoresPermitidos(['admin', 'socio']);
//     $borrarUno->setSectoresPermitidos(['admin', 'socio']);
//     $tomarFoto->setSectoresPermitidos(
//         ['admin', 'socio', 'cocinero', 'bartender', 'cervecero', 'mozo']);
//     $prepararPedido->setSectoresPermitidos(
//         ['admin', 'socio', 'cocinero', 'bartender', 'cervecero', 'mozo']);
//     $completarPedido->setSectoresPermitidos(
//         ['admin', 'socio', 'cocinero', 'bartender', 'cervecero', 'mozo']);
//     $servirPedido->setSectoresPermitidos(['admin', 'socio', 'mozo']);
//     $cobrarPedido->setSectoresPermitidos(['admin', 'socio', 'mozo']);
//     $cerrarPedido->setSectoresPermitidos(['admin', 'socio']);

//     $group->get('/disponibles', \PedidoController::class . ':TraerDisponibles')
//         ->add($traerDisponibles)
//         ->add(\AuthMiddleware::class . ':obtenerDataToken')
//         ->add(\AuthMiddleware::class . ':verificarToken');

//     $group->get('[/]', \PedidoController::class . ':TraerTodos')
//         ->add($traerTodos)
//         ->add(\AuthMiddleware::class . ':obtenerDataToken')
//         ->add(\AuthMiddleware::class . ':verificarToken');

//     $group->get('/{pedido}', \PedidoController::class . ':TraerUno')
//         ->add($traerUno)
//         ->add(\AuthMiddleware::class . ':verificarToken');

//     $group->post('[/]', \PedidoController::class . ':CargarUno')
//         ->add($cargarUno)
//         ->add(\AuthMiddleware::class . ':verificarToken');

//     $group->put('/{pedido}', \PedidoController::class . ':ModificarUno')
//         ->add($modificarUno)
//         ->add(\AuthMiddleware::class . ':verificarToken');

//     $group->delete('/{pedido}', \PedidoController::class . ':BorrarUno')
//         ->add($borrarUno)
//         ->add(\AuthMiddleware::class . ':verificarToken');

//     $group->post('/{pedido}/tomarFoto', \PedidoController::class . ':TomarFoto')
//         ->add($tomarFoto)
//         ->add(\AuthMiddleware::class . ':verificarToken');

//     $group->post('/{pedido}/prepararPedido', \PedidoController::class . ':PrepararPedido')
//         ->add($prepararPedido)
//         ->add(\AuthMiddleware::class . ':obtenerDataToken')
//         ->add(\AuthMiddleware::class . ':verificarToken');

//     $group->post('/{pedido}/completarPedido', \PedidoController::class . ':CompletarPedido')
//         ->add($completarPedido)
//         ->add(\AuthMiddleware::class . ':obtenerDataToken')
//         ->add(\AuthMiddleware::class . ':verificarToken');

//     $group->post('/{pedido}/servirPedido', \PedidoController::class . ':ServirPedido')
//         ->add($servirPedido)
//         ->add(\AuthMiddleware::class . ':verificarToken');

//     $group->post('/{pedido}/cobrarPedido', \PedidoController::class . ':CobrarPedido')
//         ->add($cobrarPedido)
//         ->add(\AuthMiddleware::class . ':verificarToken');

//     $group->post('/{pedido}/cerrarPedido', \PedidoController::class . ':CerrarPedido')
//         ->add($cerrarPedido)
//         ->add(\AuthMiddleware::class . ':verificarToken');
// });

// $app->group('/productos', function (RouteCollectorProxy $group) {
//     $traerTodos = new AuthMiddleware();
//     $traerUno = new AuthMiddleware();
//     $cargarUno = new AuthMiddleware();
//     $modificarUno = new AuthMiddleware();
//     $borrarUno = new AuthMiddleware();

//     $traerTodos->setSectoresPermitidos(
//         ['admin', 'socio', 'cocinero', 'bartender', 'cervecero', 'mozo']);
//     $traerUno->setSectoresPermitidos(
//         ['admin', 'socio', 'cocinero', 'bartender', 'cervecero', 'mozo']);
//     $cargarUno->setSectoresPermitidos(['admin', 'socio', 'mozo']);
//     $modificarUno->setSectoresPermitidos(['socio', 'admin']);
//     $borrarUno->setSectoresPermitidos(['socio', 'admin']);

//     $group->get('[/]', \ProductoController::class . ':TraerTodos')
//         ->add($traerTodos)
//         ->add(\AuthMiddleware::class . ':verificarToken');

//     $group->get('/{producto}', \ProductoController::class . ':TraerUno')
//         ->add($traerUno)
//         ->add(\AuthMiddleware::class . ':verificarToken');

//     $group->post('[/]', \ProductoController::class . ':CargarUno')
//         ->add($cargarUno)
//         ->add(\AuthMiddleware::class . ':verificarToken');

//     $group->put('/{producto}', \ProductoController::class . ':ModificarUno')
//         ->add($modificarUno)
//         ->add(\AuthMiddleware::class . ':verificarToken');

//     $group->delete('/{producto}', \ProductoController::class . ':BorrarUno')
//         ->add($borrarUno)
//         ->add(\AuthMiddleware::class . ':verificarToken');
// });

// $app->group('/mesas', function (RouteCollectorProxy $group) {
//     $traerTodos = new AuthMiddleware();
//     $traerUno = new AuthMiddleware();
//     $consultarEstado = new AuthMiddleware();
//     $cargarUno = new AuthMiddleware();
//     $modificarUno = new AuthMiddleware();
//     $borrarUno = new AuthMiddleware();

//     $traerTodos->setSectoresPermitidos(['socio', 'admin']);
//     $traerUno->setSectoresPermitidos(['socio', 'admin', 'mozo']);
//     $consultarEstado->setSectoresPermitidos(['socio', 'admin', 'mozo', 'cliente']);
//     $cargarUno->setSectoresPermitidos(['socio', 'admin', 'mozo']);
//     $modificarUno->setSectoresPermitidos(['socio', 'admin', 'mozo']);
//     $borrarUno->setSectoresPermitidos(['socio', 'admin', 'mozo']);

//     $group->get('[/]', \MesaController::class . ':TraerTodos')
//         ->add($traerTodos)
//         ->add(\AuthMiddleware::class . ':verificarToken');
//     $group->get('/{mesa}', \MesaController::class . ':TraerUno')
//         ->add($traerUno)
//         ->add(\AuthMiddleware::class . ':verificarToken');
//     $group->get('/{cod_pedido}/estado', \MesaController::class . ':ConsultarEstado')
//         ->add($consultarEstado)
//         ->add(\AuthMiddleware::class . ':verificarToken');
//     $group->post('[/]', \MesaController::class . ':CargarUno')
//         ->add($cargarUno)
//         ->add(\AuthMiddleware::class . ':verificarToken');
//     $group->put('/{mesa}', \MesaController::class . ':ModificarUno')
//         ->add($modificarUno)
//         ->add(\AuthMiddleware::class . ':verificarToken');
//     $group->delete('/{mesa}', \MesaController::class . ':BorrarUno')
//         ->add($borrarUno)
//         ->add(\AuthMiddleware::class . ':verificarToken');
// });

// $app->group('/sectores', function (RouteCollectorProxy $group) {
//     $traerTodos = new AuthMiddleware();
//     $traerUno = new AuthMiddleware();
//     $cargarUno = new AuthMiddleware();
//     $modificarUno = new AuthMiddleware();
//     $borrarUno = new AuthMiddleware();

//     $traerTodos->setSectoresPermitidos(['socio', 'admin']);
//     $traerUno->setSectoresPermitidos(['socio', 'admin']);
//     $cargarUno->setSectoresPermitidos(['socio', 'admin']);
//     $modificarUno->setSectoresPermitidos(['socio', 'admin']);
//     $borrarUno->setSectoresPermitidos(['socio', 'admin']);

//     $group->get('[/]', \SectorController::class . ':TraerTodos')
//         ->add($traerTodos)
//         ->add(\AuthMiddleware::class . ':verificarToken');
//     $group->get('/{sector}', \SectorController::class . ':TraerUno')
//         ->add($traerUno)
//         ->add(\AuthMiddleware::class . ':verificarToken');
//     $group->post('[/]', \SectorController::class . ':CargarUno')
//         ->add($cargarUno)
//         ->add(\AuthMiddleware::class . ':verificarToken');
//     $group->put('/{sector}', \SectorController::class . ':ModificarUno')
//         ->add($modificarUno)
//         ->add(\AuthMiddleware::class . ':verificarToken');
//     $group->delete('/{sector}', \SectorController::class . ':BorrarUno')
//         ->add($borrarUno)
//         ->add(\AuthMiddleware::class . ':verificarToken');
// });

// $app->group('/encuestas', function (RouteCollectorProxy $group) {
//     $traerTodos = new AuthMiddleware();
//     $traerUno = new AuthMiddleware();
//     $cargarUno = new AuthMiddleware();
//     $modificarUno = new AuthMiddleware();
//     $borrarUno = new AuthMiddleware();

//     $traerTodos->setSectoresPermitidos(['socio', 'admin']);
//     $traerUno->setSectoresPermitidos(['socio', 'admin']);
//     $cargarUno->setSectoresPermitidos(['socio', 'admin']);
//     $modificarUno->setSectoresPermitidos(['socio', 'admin']);
//     $borrarUno->setSectoresPermitidos(['socio', 'admin']);

//     $group->get('[/]', \EncuestaController::class . ':TraerTodos')
//         ->add($traerTodos)
//         ->add(\AuthMiddleware::class . ':verificarToken');
//     $group->get('/{encuesta}', \EncuestaController::class . ':TraerUno')
//         ->add($traerUno)
//         ->add(\AuthMiddleware::class . ':verificarToken');
//     $group->post('[/]', \EncuestaController::class . ':CargarUno')
//         ->add($cargarUno)
//         ->add(\AuthMiddleware::class . ':verificarToken');
//     $group->put('/{encuesta}', \EncuestaController::class . ':ModificarUno')
//         ->add($modificarUno)
//         ->add(\AuthMiddleware::class . ':verificarToken');
//     $group->delete('/{encuesta}', \EncuestaController::class . ':BorrarUno')
//         ->add($borrarUno)
//         ->add(\AuthMiddleware::class . ':verificarToken');
// });

// $app->group('/csv', function (RouteCollectorProxy $group) {
//     $group->get('/descargar', \CSVController::class . ':DescargarEntidad');
//     $group->post('/cargar', \CSVController::class . ':CargarEntidad');
// });

$app->run();
?>