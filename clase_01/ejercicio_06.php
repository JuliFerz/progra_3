<?php
/*
    Ejercicio: 06
    Enunciado: Definir un Array de 5 elementos enteros y asignar a cada uno de ellos un número (utilizar la función rand). Mediante una estructura condicional, determinar si el promedio de los números son mayores, menores o iguales que 6. Mostrar un mensaje por pantalla informando el resultado.
    Alumno: Julian Fernandez
*/

$listaNumeros = array();
$promedio = 0;

for ($i = 0; $i < 5; $i++) {
    $listaNumeros[$i] = rand(1, 10);
}
$promedio = array_sum($listaNumeros) / (count($listaNumeros));

echo "Numeros: ";
foreach ($listaNumeros as $numero) {
    echo "<li>" . $numero . "</li>";
}
if ($promedio > 6)
    echo "El promedio es mayor que 6";
else if ($promedio === 6)
    echo "El promedio es igual que 6";
else
    echo "El promedio es menor que 6";
echo "<br>";
echo "Promedio " . $promedio;
?>