<?php

require_once './controllers/ClassController.php';
require_once './models/Reserva.php';

if (
    isset($_POST['tipoCliente']) && $_POST['tipoCliente'] != null
    && isset($_POST['nroCliente']) && $_POST['nroCliente'] != null
    && isset($_POST['fechaEntrada']) && $_POST['fechaEntrada'] != null
    && isset($_POST['fechaSalida']) && $_POST['fechaSalida'] != null
    && isset($_POST['tipoHabitacion']) && $_POST['tipoHabitacion'] != null
    && isset($_POST['importeTotal']) && $_POST['importeTotal'] != null
    && isset($_FILES['imagen']) && $_FILES['imagen'] != null
) {
    try {
        ClassController::validateInputNumber($_POST['nroCliente']);
        ClassController::validateInputNumber($_POST['importeTotal']);
        ClassController::validateInputDate($_POST['fechaEntrada']);
        ClassController::validateInputDate($_POST['fechaSalida']);

        $cliente = ClassController::BuscarClienteJson(['id' => (int) $_POST['nroCliente'], 'tipoCliente' => $_POST['tipoCliente']]);
        
        $reserva = new Reserva(
            $_POST['tipoCliente'],
            (int) $_POST['nroCliente'],
            $_POST['fechaEntrada'],
            $_POST['fechaSalida'],
            $_POST['tipoHabitacion'],
            (int) $_POST['importeTotal'],
            $cliente->{'modalidadPago'},
            $_FILES['imagen']
        );
        Reserva::altaReserva($reserva);
        echo json_encode(['success' => 'Reserva creada con exito.']);

    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Revise los datos de entrada.']);
}
?>