<?php
/*
    Ejercicio: 05
    Enunciado: Realizar un programa que en base al valor numérico de una variable $num, pueda mostrarse por pantalla, el nombre del número que tenga dentro escrito con palabras, para los números entre el 20 y el 60. 
    Por ejemplo, si $num = 43 debe mostrarse por pantalla “cuarenta y tres”.
    Alumno: Julian Fernandez
*/

$num = 42;
$numStr = "";
$decenas = ["Diez", "Veinte", "Treinta", "Cuarenta", "Cincuenta", "Sesenta"];
$unidades = ["uno", "dos", "tres", "cuatro", "cinco", "seis", "siete", "ocho", "nueve"];

if ($num >= 20 && $num <= 60) {
    $firstNumber = intval(strval($num)[0]);
    $secondNumber = intval(strval($num)[1]);

    if ($firstNumber == 2 && $secondNumber > 0){
        $decenas[$firstNumber - 1] = "Veinti";
        $numStr = $decenas[$firstNumber - 1] . $unidades[$secondNumber - 1];
    } else {
        $numStr = $decenas[$firstNumber - 1] . ($firstNumber > 2 && $secondNumber > 0 ? " y " . $unidades[$secondNumber - 1] : $numStr);
    }

    echo $numStr;
} else {
    echo "Elegir un numero entre 20 y 60.";
}
?>