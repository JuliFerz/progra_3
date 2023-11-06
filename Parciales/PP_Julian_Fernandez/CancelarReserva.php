<?php

require_once './controllers/ClassController.php';

if (
    isset($_POST['nroCliente']) && $_POST['nroCliente'] != null
    && isset($_POST['tipoCliente']) && $_POST['tipoCliente'] != null
    && isset($_POST['idReserva']) && $_POST['idReserva'] != null
) {
    try {
        ClassController::validateInputNumber($_POST['nroCliente']);
        ClassController::validateInputNumber($_POST['idReserva']);
        ClassController::BuscarClienteJson(['id' => (int) $_POST['nroCliente'], 'tipoCliente' => $_POST['tipoCliente']]);

        if (!ClassController::BuscarEnJson((int) $_POST['idReserva'], 'id', 'reservas')) {
            throw new Exception('No se encontro la reserva.');
        }
        ClassController::CancelarReserva((int) $_POST['idReserva']);
        echo json_encode(['success' => 'Reserva cancelada con exito.']);

    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Revise los datos de entrada.']);
}
?>