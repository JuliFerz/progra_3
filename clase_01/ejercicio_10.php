<?php
/*
    Ejercicio: 10
    Enunciado: Realizar las líneas de código necesarias para generar un Array asociativo y otro indexado que contengan como elementos tres Arrays del punto anterior cada uno. Crear, cargar y mostrar los Arrays de Arrays.
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

$vIndexado = [
    $lapicera1,
    $lapicera2,
    $lapicera3
];

$vAsociativo = [
    'lapicera1' => $lapicera1,
    'lapicera2' => $lapicera2,
    'lapicera3' => $lapicera3
];

echo "Vector indexado:";
echo '<br>';
for ($i = 0; $i < count($vIndexado); $i++) {
    printf('Lapicera Nro. %s: <br>', $i + 1);
    foreach ($vIndexado[$i] as $key => $value) {
        echo $key . ' = ' . $value . '<br>';
    }
    echo '<br>';
}

echo "Vector asociativo:";
echo '<br>';
foreach ($vAsociativo as $nombre => $datos) {
    printf('Nombre "%s": <br>', $nombre);

    foreach ($datos as $key => $value) {
        echo $key . ' = ' . $value . '<br>';
    }
    echo '<br>';
}
?>