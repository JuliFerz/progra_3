<?php
/*
    Ejercicio: 07
    Enunciado: Generar una aplicación que permita cargar los primeros 10 números impares en un Array. Luego imprimir (utilizando la estructura for) cada uno en una línea distinta (recordar que el salto de línea en HTML es la etiqueta <br/>). Repetir la impresión de los números utilizando las estructuras while y foreach.
    Alumno: Julian Fernandez
*/
$numerosImpares = [];

$i = 0;
while(count($numerosImpares) < 10){
    $i++;
    if ($i % 2 == 1)
        array_push($numerosImpares, $i);
    
    // O tambien:
    // ($i % 2 == 1) ? array_push($numerosImpares, $i) : "";
}
echo "[FOR] Numeros:" . "<br>";
for ($i = 0; $i < count($numerosImpares); $i++) {
    echo $numerosImpares[$i] . "<br>";
}

echo "<br>" . "[WHILE] Numeros:" . "<br>";
$i = 0;
while ($i < count($numerosImpares)) {
    echo $numerosImpares[$i] . "<br>";
    $i++;
}

echo "<br>" . "[FOREACH] Numeros:" . "<br>";
foreach ($numerosImpares as $nro) {
    echo $nro . "<br>";
}
?>