<?php
date_default_timezone_set("America/Argentina/Buenos_Aires");

if (isset($_GET['accion'])) {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            switch ($_GET['accion']) {
                case 'usuarios.json':
                    include 'listado.php';
                    break;
                case 'productos.json':
                case 'vehiculos.json':
                    echo json_encode(['error' => 'Archivo aun no implementado.']);
                    break;
                default:
                    echo json_encode(['error' => 'No se encontro un archivo para retornar. El archivo a retornar debe contener la extension (.csv o .json)']);
                    break;
            }
            break;
        case 'POST':
            switch ($_GET['accion']) {
                case 'registro':
                    include 'registro.php';
                    break;
                case 'altaProducto':
                    include 'altaProducto.php';
                    break;
                case 'realizarVenta':
                    include 'RealizarVenta.php';
                    break;
                default:
                    echo json_encode(['error' => 'Accion no valida']);
                    break;
            }
            break;
        default:
            echo json_encode(['error' => 'Verbo HTTP no contemplado. Solo GET o POST']);
            break;
    }
} else {
    echo json_encode(['error' => 'Falta parametro \'accion\'']);
}
?>