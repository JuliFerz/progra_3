<?php
/*
    Ejercicio: 04
    Enunciado: Escribir un programa que use la variable $operador que pueda almacenar los símbolos matemáticos: ‘+’, ‘-’, ‘/’ y ‘*’; y definir dos variables enteras $op1 y $op2. De acuerdo al símbolo que tenga la variable $operador, deberá realizarse la operación indicada y mostrarse el resultado por pantalla.
    Alumno: Julian Fernandez
*/
$operador = "/";
$op1 = 2;
$op2 = 3;
$resultado = 0;

switch($operador){
    case "+":
        $resultado = $op1 + $op2;
        break;
    case "-":
        $resultado = $op1 - $op2;
        break;
    case "/":
        $resultado = $op1 / $op2;
        break;
    case "*":
        $resultado = $op1 * $op2;
        break;
    default:
        break;
}

print($resultado ? "El resultado esultado de $op1 $operador $op2 es: $resultado" : "No se reconoce el operador");
?>