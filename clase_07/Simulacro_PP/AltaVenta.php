<?php
include_once('modelos/Pizza.php');
require_once 'Controller.php';

$controller = new Controller();

function validateNumber($strNumber)
{
    if (!preg_match('/^[0-9]+$/', $strNumber)) {
        throw new Exception('Se encontro un caracter no numerico en \'' . $strNumber . '\'.');
    }
}

if (isset($_POST['email']) && isset($_POST['sabor']) && isset($_POST['tipo']) && isset($_POST['cantidad'])) {

    try {
        validateNumber($_POST['cantidad']);

        $arrPizza = $controller->BuscarPizza($_POST['sabor'], $_POST['tipo'], (int) $_POST['cantidad'], './Pizza.json');

        if ($arrPizza) {
            echo "SI se encontró la pizza";
            $controller->DescontarStockPizza($arrPizza/* , (int) $_POST['cantidad'] */);
        } else {
            echo "NO se encontró la pizza";
        }

    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    /* if (validateNumber($_GET['precio']) && $_GET['cantidad']) {
        if (Pizza::AlreadyExist($_GET)){
            echo json_encode(['success' => 'Operacion exitosa. Pizza actualizada correctamente.']);
        } else {
            $pizza = new Pizza(
                rand(1, 10000),
                $_GET['sabor'],
                (int)$_GET['precio'],
                $_GET['tipo'],
                (int)$_GET['cantidad']
            );
            Pizza::AltaPizza($pizza);
            echo json_encode(['success' => 'Operacion exitosa. Pizza generada y guardada en ./Pizza.json']);

        };
    } else {
        echo json_encode(['error' => 'Operacion fallida. Campos precio y/o cantidad con caracteres no numericos.']);
    } */
} else {
    echo json_encode(['error' => 'Operacion fallida. Revise los datos de entrada.']);
}
?>