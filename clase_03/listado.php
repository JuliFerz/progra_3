<?php
include_once('Usuario.php');

if (isset($_GET['file'])) {
    $file = $_GET['file'] . '.csv';
    switch($_GET['file']){
        case 'usuarios':
            Usuario::LeerUsuarios($file);
            break;
        case 'productos':
        case 'vehículos':
            echo 'Aun no implementado';
            echo '<br>';
            break;
        default:
            echo 'Error, no se encontró un archivo "<b>' . $_GET['file'] . '</b>" para retornar';
            echo '<br>';
    }
    echo 'Operación finalizada.';
} else {
    echo 'Operación fallida.<br>Revisar listado deseado a retornar:';
    echo '<br>';
    echo '<li>Usuarios</li>';
    echo '<li>Productos</li>';
    echo '<li>Vehículos</li>';
}
?>