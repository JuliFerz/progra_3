<?php
// Comienzo de la sesión
session_start();

if (isset($_SESSION["usuario"])) {
    echo "El valor 'usuario' de la sesion es: " . $_SESSION["usuario"];
    echo "<br>";
    echo "<br>";
    var_dump($_SESSION);
} else {
    echo "Sesion iniciada y con usuario";
    // Guardar datos de sesión
    $_SESSION["usuario"] = "Julian";
}
?>