<?php

require_once './controllers/ClassController.php';
parse_str(file_get_contents("php://input"), $putData);

if (
    isset($putData['nroCliente']) && $putData['nroCliente'] != null
    && isset($putData['nombre']) && $putData['nombre'] != null
    && isset($putData['apellido']) && $putData['apellido'] != null
    && isset($putData['tipoDoc']) && $putData['tipoDoc'] != null
    && isset($putData['nroDoc']) && $putData['nroDoc'] != null
    && isset($putData['email']) && $putData['email'] != null
    && isset($putData['tipoCliente']) && $putData['tipoCliente'] != null
    && isset($putData['pais']) && $putData['pais'] != null
    && isset($putData['ciudad']) && $putData['ciudad'] != null
    && isset($putData['telefono']) && $putData['telefono'] != null
) {
    try {
        ClassController::validateInputNumber($putData['nroCliente']);
        ClassController::validateInputNumber($putData['nroDoc']);
        ClassController::validateInputNumber($putData['telefono']);

        ClassController::BuscarClienteJson(['id' => (int) $putData['nroCliente'], 'tipoCliente' => $putData['tipoCliente']]);
        ClassController::ActualizarClienteJson($putData);
        echo json_encode(['success' => 'Cliente actualizado con exito.']);

    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Operacion fallida. Revise los datos de entrada correspondientes a los de un cliente.']);
}
?>