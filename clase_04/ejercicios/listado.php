<?php
include_once('modelos/Usuario.php');

Usuario::LeerUsuarios($_GET['accion']);

?>