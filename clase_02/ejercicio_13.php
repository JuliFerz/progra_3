<?php
/*
    Ejercicio: 13
    Enunciado: Crear una función que reciba como parámetro un string ($palabra) y un entero ($max). La función validará que la cantidad de caracteres que tiene $palabra no supere a $max y además deberá determinar si ese valor se encuentra dentro del siguiente listado de palabras válidas: 
        “Recuperatorio”, “Parcial” y “Programacion”.
    Los valores de retorno serán: 1 si la palabra pertenece a algún elemento del listado. 0 en caso contrario.
    Alumno: Julian Fernandez
*/
$palabrasValidas = ["Recuperatorio", "Parcial", "Programacion"];
$str = "Recuperatorio";
$maximo = 15;

Function ValidarString($string, $max){
    global $palabrasValidas;
    return strlen($string) <= $max && in_array($string, $palabrasValidas) ? 1 : 0;
};

if (ValidarString($str, $maximo)){
    printf("SI: La palabra %s pertenece al listado de palabras validas, y no supera el máximo: %d", $str, $maximo);
} else {
    printf("NO: La palabra %s no pertenece al listado de palabras validas, o no supera el máximo: %d", $str, $maximo);
}
?>