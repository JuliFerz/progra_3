<?php

require_once './controllers/ClassController.php';

if (isset($_GET['listado']) && $_GET['listado'] != null) {
    try {

        switch ($_GET['listado']) {
            case 'totalReserva':
                totalReserva();
                break;
            case 'porCliente':
                porCliente();
                break;
            case 'entreFechas':
                entreFechas();
                break;
            case 'porHabitacion':
                porHabitacion();
                break;
            case 'canceladasPorTipoClienteYFecha':
                canceladasPorTipoClienteYFecha();
                break;
            case 'canceladasPorIdCliente':
                canceladasPorIdCliente();
                break;
            case 'canceladasEntreFechas':
                canceladasEntreFechas();
                break;
            case 'canceladasPorTipoCliente':
                canceladasPorTipoCliente();
                break;
            case 'porIdCliente':
                porCliente();
                break;
            case 'porTipoModalidad':
                porTipoModalidad();
                break;
            default:
                echo json_encode(['error' => 'No se encontro el listado para retornar']);
                break;
        }
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Revise los datos de entrada.']);
}


function totalReserva()
{
    $fecha = '';
    if (isset($_GET['fecha']) && $_GET['fecha'] != null) {
        ClassController::validateInputDate($_GET['fecha']);
        $fecha = $_GET['fecha'];
    }

    $total = ClassController::ObtenerTotalReservas($fecha);
    echo json_encode(['success' => 'El total de reservas es $' . $total]);
}

function porCliente()
{
    if (!isset($_GET['idCliente']) || $_GET['idCliente'] == null) {
        throw new Exception('Para listar por cliente debe proveer un id de cliente.');
    }
    ClassController::validateInputNumber($_GET['idCliente']);

    $reservas = ClassController::BuscarReservaPorCliente((int) $_GET['idCliente']);
    echo '<pre>' . var_export($reservas, true) . '</pre>';
}

function entreFechas()
{
    if (
        !isset($_GET['fechaDesde']) || $_GET['fechaDesde'] == null
        && !isset($_GET['fechaHasta']) || $_GET['fechaHasta'] == null
    ) {
        throw new Exception('Deben estar presentes los campos \'fechaDesde\' y \'fechaHasta\'');
    }
    ClassController::validateInputDate($_GET['fechaDesde']);
    ClassController::validateInputDate($_GET['fechaHasta']);

    $reservas = ClassController::BuscarReservasPorFechas($_GET['fechaDesde'], $_GET['fechaHasta']);
    echo '<pre>' . var_export($reservas, true) . '</pre>';
}

function porHabitacion()
{
    if (!isset($_GET['habitacion']) || $_GET['habitacion'] == null) {
        throw new Exception('Deben estar presente el tipo de habitacion para listar.');
    }

    $reservas = ClassController::BuscarReservasPorHabitacion($_GET['habitacion']);
    echo '<pre>' . var_export($reservas, true) . '</pre>';
}

function canceladasPorTipoClienteYFecha()
{
    $fecha = '';

    if (isset($_GET['fecha']) && $_GET['fecha'] != null) {
        ClassController::validateInputDate($_GET['fecha']);
        $fecha = $_GET['fecha'];
    }
    if (!isset($_GET['tipoCliente']) || $_GET['tipoCliente'] == null) {
        throw new Exception('Para listar por cliente debe proveer un tipo de cliente.');
    }

    $total = ClassController::ObtenerTotalReservasPorClienteYFecha($_GET['tipoCliente'], $fecha);
    echo json_encode(['success' => 'El total de reservas CANCELADAS es $' . $total]);
}

function canceladasPorIdCliente()
{
    if (!isset($_GET['idCliente']) || $_GET['idCliente'] == null) {
        throw new Exception('Para listar las reservas canceladas por cliente debe proveer un id de cliente.');
    }

    $total = ClassController::ObtenerReservasCanceladasPorCliente((int) $_GET['idCliente']);
    echo '<pre>' . var_export($total, true) . '</pre>';
}

function canceladasEntreFechas()
{
    if (
        !isset($_GET['fechaDesde']) || $_GET['fechaDesde'] == null
        && !isset($_GET['fechaHasta']) || $_GET['fechaHasta'] == null
    ) {
        throw new Exception('Deben estar presentes los campos \'fechaDesde\' y \'fechaHasta\'');
    }
    ClassController::validateInputDate($_GET['fechaDesde']);
    ClassController::validateInputDate($_GET['fechaHasta']);

    $reservas = ClassController::BuscarReservasCanceladasPorFechas($_GET['fechaDesde'], $_GET['fechaHasta']);
    echo '<pre>' . var_export($reservas, true) . '</pre>';
}

function canceladasPorTipoCliente()
{
    if (!isset($_GET['tipoCliente']) || $_GET['tipoCliente'] == null) {
        throw new Exception('Para listar por cliente debe proveer un tipo de cliente.');
    }

    $total = ClassController::ObtenerReservasCanceladasPorTipoCliente($_GET['tipoCliente']);
    echo '<pre>' . var_export($total, true) . '</pre>';
}

function porTipoModalidad()
{
    if (!isset($_GET['tipoModalidad']) || $_GET['tipoModalidad'] == null) {
        throw new Exception('Para listar las reservas indique un tipo de modalidad.');
    }

    $total = ClassController::ObtenerReservasPorTipoModalidad($_GET['tipoModalidad']);
    echo '<pre>' . var_export($total, true) . '</pre>';
}

?>