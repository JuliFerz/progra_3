<?php

require_once 'modelos/Venta.php';
require_once 'Controller.php';

$controller = new Controller();

function validateNumber($strNumber)
{
    if (!preg_match('/^[0-9]+$/', $strNumber)) {
        throw new Exception('No se pudo hacer. Se encontro un caracter no numerico en los campos EAN, Stock o Precio.');
    }
}

if (isset($_POST['ean']) && isset($_POST['idUsuario']) && isset($_POST['cantidad'])) {
    try {
        validateNumber($_POST['ean']);
        validateNumber($_POST['idUsuario']);
        validateNumber($_POST['cantidad']);

        if (
            $controller->BuscarEnJson((int) $_POST['idUsuario'], '_id', './usuarios.json')
            && $controller->BuscarEnJson((int) $_POST['ean'], '_ean', './productos.json')
            && $controller->ControlarStock((int) $_POST['ean'], (int) $_POST['cantidad'])
        ) {
            $venta = new Venta(
                rand(1, 10000),
                (int) $_POST['ean'],
                (int) $_POST['idUsuario'],
                (int) $_POST['cantidad']
            );
            Venta::AltaVenta($venta);
            echo json_encode(['success' => 'Venta realizada']);
        } else {
            echo json_encode(['error' => 'No se pudo hacer']);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Operacion fallida. Revise los datos de entrada correspondientes a los de un producto.']);
}