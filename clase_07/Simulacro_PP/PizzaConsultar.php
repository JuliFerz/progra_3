<?php

require_once 'modelos/Pizza.php';

if (isset($_POST['sabor']) && isset($_POST['tipo'])) {
    echo Pizza::BuscarPizza($_POST['sabor'], $_POST['tipo']);
} else {
    echo json_encode(['error' => 'Operacion fallida. Revise los datos de entrada correspondientes a los de una pizza.']);
}

?>