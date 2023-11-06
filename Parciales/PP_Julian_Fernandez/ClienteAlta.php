<?php

require_once './controllers/ClassController.php';
require_once './models/Cliente.php';

if (
    isset($_POST['nombre']) && $_POST['nombre'] != null
    && isset($_POST['apellido']) && $_POST['apellido'] != null
    && isset($_POST['tipoDoc']) && $_POST['tipoDoc'] != null
    && isset($_POST['nroDoc']) && $_POST['nroDoc'] != null
    && isset($_POST['email']) && $_POST['email'] != null
    && isset($_POST['tipoCliente']) && $_POST['tipoCliente'] != null
    && isset($_POST['pais']) && $_POST['pais'] != null
    && isset($_POST['ciudad']) && $_POST['ciudad'] != null
    && isset($_POST['telefono']) && $_POST['telefono'] != null
    && isset($_FILES['imagen']) && $_FILES['imagen'] != null
) {
    try {
        ClassController::validateInputNumber($_POST['nroDoc']);
        ClassController::validateInputNumber($_POST['telefono']);
        $modalidadPago = isset($_POST['modPago']) ? $_POST['modPago'] : '';

        
        if (ClassController::BuscarEnJson((int) $_POST['nroDoc'], 'nroDoc', 'hoteles') ) {
            echo json_encode(['error' => 'Ya existe el usuario. No se puede crear.']);
        } else {
            $cliente = new Cliente(
                $_POST['nombre'],
                $_POST['apellido'],
                $_POST['tipoDoc'],
                (int) $_POST['nroDoc'],
                $_POST['email'],
                $_POST['tipoCliente'],
                $_POST['pais'],
                $_POST['ciudad'],
                (int) $_POST['telefono'],
                $modalidadPago,
                $_FILES['imagen']
            );
            Cliente::altaCliente($cliente);
            echo json_encode(['success' => 'Cliente dado de alta con exito.']);
        }


    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Operacion fallida. Revise los datos de entrada correspondientes a los de un cliente.']);
}
?>