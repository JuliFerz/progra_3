<?php
if (isset($_GET['accion'])) {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            switch ($_GET['accion']) {
                case 'sesion':
                    include 'GET/sesion.php';
                    break;
                case 'cookies':
                    include 'GET/cookies.php';
                    break;
                case 'json':
                    include 'GET/json.php';
                    break;
                default:
                    echo 'Valor de parametro "accion" inválido';
                    break;
            }
            break;
        case 'POST':
            switch ($_GET['accion']) {
                case 'archivo':
                    include 'POST/archivo.php';
                    break;
                default:
                    echo 'Valor de parametro "accion" inválido';
                    break;
            }
            break;
        default:
            echo 'Verbos permitidos: GET o POST';
    }
} else {
    echo 'Falta parametro "accion"';
}
?>