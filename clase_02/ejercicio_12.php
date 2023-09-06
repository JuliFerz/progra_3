<?php
/*
    Ejercicio: 12
    Enunciado: Realizar el desarrollo de una función que reciba un Array de caracteres y que invierta el orden de las letras del Array.
               Ejemplo: Se recibe la palabra “HOLA” y luego queda “ALOH”.
    Alumno: Julian Fernandez
*/
$str = "Hello world.";

$inverter = function($strArr){
    // manual:
    /* for($i = count($strArr); $i > 0; $i--) {
        echo $strArr[$i-1];
    } */
    // reverse:
    foreach(array_reverse($strArr) as $letter){
        echo $letter;
    }
};
$inverter(str_split($str));
?>