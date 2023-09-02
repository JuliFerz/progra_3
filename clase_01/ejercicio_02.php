<?php
/*
    Ejercicio: 02
    Enunciado: Obtenga la fecha actual del servidor (función date) y luego imprímala dentro de la página con distintos formatos (seleccione los formatos que más le guste). Además indicar que estación del año es. Utilizar una estructura selectiva múltiple.
    Alumno: Julian Fernandez
*/
date_default_timezone_set("America/Argentina/Buenos_Aires");
$estacion = "";

printf("Fecha actual del servidor (%s)", date("d/m/Y G:i"));
echo "<br>";
echo date("d/m/Y");
echo "<br>";
echo date("Y/m/d");
echo "<br>";
echo date("m-Y-d");
echo "<br>";

/* 
    Estaciones:
    . Verano (21 del "12" a 20 del "3").
    . Otoño (21 del "3" a 20 del "6").
    . Invierno (21 del "6" a 20 del "9").
    . Primavera (21 del "9" a 20 del "12"). 
*/
switch (intval(date("m"))) {
    case 12:
    case 1:
    case 2:
        $estacion = "Verano";
        break;
    case 3:
    case 4:
    case 5:
        $estacion = "Otoño";
        break;
    case 6:
    case 7:
    case 8:
        $estacion = "Invierno";
        break;
    case 9:
    case 10:
    case 11:
        $estacion = "Primavera";
        break;
}
echo "Estacion: $estacion";
?>