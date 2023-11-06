<?php

require_once './controllers/ClassController.php';

if (
    isset($_POST['idReserva']) && $_POST['idReserva'] != null
    && isset($_POST['importe']) && $_POST['importe'] != null
    && isset($_POST['motivo']) && $_POST['motivo'] != null
) {
    try {
        ClassController::validateInputNumber($_POST['idReserva']);
        ClassController::validateInputNumber($_POST['importe']);

        if (!ClassController::BuscarEnJson((int) $_POST['idReserva'], 'id', 'reservas')) {
            throw new Exception('No se encontro la reserva.');
        }
        $datosReserva = ClassController::AjustarReserva((int) $_POST['idReserva'], (int) $_POST['importe']);

        ClassController::GrabarAjusteReserva((int) $_POST['idReserva'], $datosReserva, $_POST['motivo']);
        echo json_encode(['success' => 'Reserva ajustada con exito.']);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Revise los datos de entrada.']);
}
?>