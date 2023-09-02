<?php
/*
    Ejercicio: 08
    Enunciado: Imprima los valores del vector asociativo siguiente usando la estructura de control foreach:
        $v[1]=90; $v[30]=7; $v['e']=99; $v['hola']= 'mundo';
    Alumno: Julian Fernandez
*/
// $vectorAsociativo = [1 => 90, 30 => 7, 'e' => 99, 'hola' => 'mundo'];
$v = [];
$v[1]=90; $v[30]=7; $v['e']=99; $v['hola']= 'mundo';

foreach($v as $key => $value){
    echo $key . " = " . $value . "<br>";
}
?>