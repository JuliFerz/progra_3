<?php

require_once './controllers/ClassController.php';

if (
    isset($_POST['tipoCliente']) && $_POST['tipoCliente'] != null
    && isset($_POST['nroCliente']) && $_POST['nroCliente'] != null
) {
    try {
        $cliente = ClassController::BuscarClienteJson(['id' => (int) $_POST['nroCliente'], 'tipoCliente' => $_POST['tipoCliente']]);
        echo '<pre>' . var_export(['pais' => $cliente->{'pais'}, 'ciudad' => $cliente->{'ciudad'}, 'telefono' => $cliente->{'telefono'}], true) . '</pre>';
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Operacion fallida. Revise los datos de entrada']);
}
?>