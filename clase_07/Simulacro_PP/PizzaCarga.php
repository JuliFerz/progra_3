<?php
include_once('modelos/Pizza.php');

function validateNumber($strNumber){
    if (!preg_match('/^[0-9]+$/', $strNumber)) {
        throw new Exception('Se encontro un caracter no numerico.');
    }
}

if (isset($_GET['sabor']) && isset($_GET['precio']) && isset($_GET['tipo']) && isset($_GET['cantidad'])) {
    try {
        validateNumber($_GET['precio']);
        validateNumber($_GET['cantidad']);
        if (Pizza::AlreadyExist($_GET)){
            echo json_encode(['success' => 'Pizza actualizada correctamente.']);
        } else {
            $pizza = new Pizza(
                rand(1, 10000),
                $_GET['sabor'],
                (int)$_GET['precio'],
                $_GET['tipo'],
                (int)$_GET['cantidad']
            );
            Pizza::AltaPizza($pizza);
            echo json_encode(['success' => 'Pizza generada y guardada en ./Pizza.json']);
        };
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Revise los datos de entrada.']);
}
?>