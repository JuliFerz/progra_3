<?php
include_once('Usuario.php');

$archivo = 'usuarios.csv';

if (isset($_POST['clave']) && isset($_POST['mail'])){
    $resultado = Usuario::BuscarUsuario($archivo, $_POST['clave'], $_POST['mail']);
    echo $resultado;
} else {
    echo 'Por favor, ingrese una clave y un mail para validar el login.';
}

?>