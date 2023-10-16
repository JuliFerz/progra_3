<?php
// Array Indexado PHP a JSON
$array = array("Jorge", "Hernan", "Pedro", "Marta");
$json = json_encode($array);

echo "1 -> " . $json;
echo "<br>";


// Array asoc PHP a JSON
$array_asoc = array("nombre" => "Jorge", "edad" => 32);
$json_b = json_encode($array_asoc);

echo "2 -> " .  $json_b;
echo "<br>";

// Clase/OBJ PHP a JSON
$clase = new StdClass();
$clase->nombre = 'Jorge';
$clase->edad = 32;

$json_c = json_encode($clase);

echo "3 -> " .  $json_c;
echo "<br>";

// JSON a PHP
$json_obj = '{"nombre":"Pedro", "edad":37}';
$obj = json_decode($json_obj);

echo "4 -> ";
var_dump($obj);
?>