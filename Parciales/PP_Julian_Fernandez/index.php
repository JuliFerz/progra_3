<?php
date_default_timezone_set("America/Argentina/Buenos_Aires");

if (isset($_GET['accion'])) {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            switch ($_GET['accion']) {
                case 'ConsultaReservas':
                    require './ConsultaReservas.php';
                    break;
                default:
                    echo json_encode(['error' => 'Accion invalida.']);
                    break;
            }
            break;
        case 'POST':
            switch ($_GET['accion']) {
                case 'ClienteAlta':
                    require './ClienteAlta.php';
                    break;
                case 'ConsultarCliente':
                    require './ConsultarCliente.php';
                    break;
                case 'ReservaHabitacion':
                    require './ReservaHabitacion.php';
                    break;
                case 'CancelarReserva':
                    require './CancelarReserva.php';
                    break;
                case 'AjusteReserva':
                    require './AjusteReserva.php';
                    break;
                default:
                    echo json_encode(['error' => 'Accion no valida']);
                    break;
            }
            break;
        case 'PUT':
            switch ($_GET['accion']) {
                case 'ModificarCliente':
                    require './ModificarCliente.php';
                    break;
                default:
                    echo json_encode(['error' => 'Accion invalida.']);
                    break;
            }
            break;
        default:
            echo json_encode(['error' => 'Verbo HTTP no contemplado.']);
            break;
        case 'DELETE':
            switch ($_GET['accion']) {
                case 'BorrarCliente':
                    require './BorrarCliente.php';
                    break;
                default:
                    echo json_encode(['error' => 'Accion invalida.']);
                    break;
            }
            break;
    }
} else {
    echo json_encode(['error' => 'Falta parametro \'accion\'']);
}

?>