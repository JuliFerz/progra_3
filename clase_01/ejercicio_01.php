<?php
/*
    Ejercicio: 01
    Enunciado: Confeccionar un programa que sume todos los números enteros desde 1 mientras la suma no supere a 1000. Mostrar los números sumados y al finalizar el proceso indicar cuantos números se sumaron.
    Alumno: Julian Fernandez
*/
$suma = 0;
$numerosSumados = array();

$i = 1;
while($suma + $i <= 1000){
    $numerosSumados[$i-1] = $i;
    $suma += $i;
    $i++;
}

echo "<br>";
print("Numeros sumados<br>");
foreach ($numerosSumados as $numero) {
    print("$numero, ");
}
echo "<br>";
echo "Total de numeros sumados (desde 1): " . count($numerosSumados);
?>