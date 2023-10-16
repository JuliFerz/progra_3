<?php
if (isset($_COOKIE['prueba'])) {
    echo "<p>La cookie está creada. Su valor es: " . $_COOKIE['prueba'] . "</p>";
} else {
    echo "<p>La cookie no existe, la creamos</p>";
    // Se crea una cookie cuyo tiempo de vida será 1 minuto
    setcookie("prueba", "HOLA COMO ESTAN", time() + (60 * 1));
}
?>