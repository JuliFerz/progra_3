<?php
/*
    Ejercicio: 09
    Enunciado: Realizar las líneas de código necesarias para generar un Array asociativo $lapicera, que contenga como elementos: ‘color’, ‘marca’, ‘trazo’ y ‘precio’. Crear, cargar y mostrar tres lapiceras.
    Alumno: Julian Fernandez
*/
$lapicera = [
    'Color' => '',
    'Marca' => '',
    'Trazo' => '',
    'Precio' => 0,
];

$lapicera1 = $lapicera;
$lapicera1['Color'] = 'Rojo';
$lapicera1['Marca'] = 'Castell';
$lapicera1['Trazo'] = 'Fino';
$lapicera1['Precio'] = 1200;

$lapicera2 = $lapicera;
$lapicera2['Color'] = 'Verde';
$lapicera2['Marca'] = 'Castell';
$lapicera2['Trazo'] = 'Grueso';
$lapicera2['Precio'] = 1500;

$lapicera3 = $lapicera;
$lapicera3['Color'] = 'Negro';
$lapicera3['Marca'] = 'Bic';
$lapicera3['Trazo'] = 'Fino';
$lapicera3['Precio'] = 500;

$lapiceras = [$lapicera1, $lapicera2, $lapicera3];
for ($i = 0; $i < count($lapiceras); $i++) {
    printf('Lapicera Nro. %s: <br>', $i + 1);
    foreach ($lapiceras[$i] as $key => $value) {
        echo $key . ' = ' . $value . '<br>';
    }
    echo '<br>';
}


?>