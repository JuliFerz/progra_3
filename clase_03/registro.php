<?php
include_once('Usuario.php');

if (isset($_POST['nombre']) && isset($_POST['clave']) && isset($_POST['mail'])) {
    $usuario = new Usuario(
        $_POST['nombre'],
        $_POST['clave'],
        $_POST['mail']
    );
    Usuario::AltaUsuario($usuario);
    echo 'Operación exitosa.<br>Usuario generado y guardado en ./usuarios.csv';
} else {
    echo 'Operación fallida.<br>Revise los datos de entrada correspondientes a los de un usuario:';
    echo '<br>';
    echo '<li>Nombre</li>';
    echo '<li>Clave</li>';
    echo '<li>Mail</li>';
}
?>