<?php

require_once 'modelos/Producto.php';

function validateNumber($strNumber)
{
    if (!preg_match('/^[0-9]+$/', $strNumber)) {
        throw new Exception('No se pudo hacer. Se encontro un caracter no numerico en los campos EAN, Stock o Precio.');
    }
}

if (isset($_POST['ean']) && isset($_POST['nombre']) && isset($_POST['tipo']) && isset($_POST['stock']) && isset($_POST['precio'])) {
    try {
        validateNumber($_POST['ean']);
        validateNumber($_POST['stock']);
        validateNumber($_POST['precio']);
        $producto = new Producto(
            rand(1, 10000),
            (int) $_POST['ean'],
            $_POST['nombre'],
            $_POST['tipo'],
            (int) $_POST['stock'],
            (int) $_POST['precio'],
        );
        $res = Producto::AltaProducto($producto);
        echo json_encode(['success' => $res]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Operacion fallida. Revise los datos de entrada correspondientes a los de un producto.']);
}
