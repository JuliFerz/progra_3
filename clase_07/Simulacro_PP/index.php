<?php
date_default_timezone_set("America/Argentina/Buenos_Aires");

if (isset($_GET['accion'])) {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            switch ($_GET['accion']) {
                case 'PizzaCarga':
                    include 'PizzaCarga.php';
                    break;
                default:
                    echo json_encode(['error' => 'Accion invalida.']);
                    break;
            }
            break;
        case 'POST':
            switch ($_GET['accion']) {
                case 'PizzaConsultar':
                    include 'PizzaConsultar.php';
                    break;
                case 'AltaVenta':
                    include 'AltaVenta.php';
                    break;
                default:
                    echo json_encode(['error' => 'Accion no valida']);
                    break;
            }
            break;
        default:
            echo json_encode(['error' => 'Verbo HTTP no contemplado.']);
            break;
    }
} else {
    echo json_encode(['error' => 'Falta parametro \'accion\'']);
}
?>