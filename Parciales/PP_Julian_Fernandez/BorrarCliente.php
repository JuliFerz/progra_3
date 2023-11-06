<?php

require_once './controllers/ClassController.php';
parse_str(file_get_contents("php://input"), $delData);

if (
    isset($delData['nroDoc']) && $delData['nroDoc'] != null
    && isset($delData['tipoCliente']) && $delData['tipoCliente'] != null
    && isset($delData['nroCliente']) && $delData['nroCliente'] != null
) {
    try {
        ClassController::validateInputNumber($delData['nroDoc']);
        ClassController::validateInputNumber($delData['nroCliente']);
        $clienteData = ClassController::BuscarClienteJson(['id' => (int) $delData['nroCliente'], 'tipoCliente' => $delData['tipoCliente']]);
        if (!ClassController::BuscarEnJson((int) $delData['nroDoc'], 'nroDoc', 'hoteles')) {
            throw new Exception('No se encontro un cliente con ese nro de documento.');
        }
        ClassController::BorrarCliente($clienteData);

    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Revise los datos de entrada.']);
}
?>