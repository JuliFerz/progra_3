<?php
include_once('modelos/Usuario.php');

if (isset($_POST['nombre']) && isset($_POST['clave']) && isset($_POST['mail']) && $_FILES['imagen']) {
    $usuario = new Usuario(
        rand(1, 10000),
        $_POST['nombre'],
        $_POST['clave'],
        $_POST['mail'],
        date('Y-m-d_H:m:s'),
        $_FILES['imagen']
    );
    Usuario::AltaUsuario($usuario, 'json');
    echo json_encode(['success' => 'Operacion exitosa. Usuario generado y guardado en ./usuarios.json']);
} else {
    echo json_encode(['error' => 'Operacion fallida. Revise los datos de entrada correspondientes a los de un usuario.']);
}
?>