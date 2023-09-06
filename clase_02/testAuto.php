<?php
/*
    -> Del ejercicio 17
*/
include "ejercicio_17.php";

// dos objetos “Auto” de la misma marca y distinto color.
$auto1 = new Auto("Chevrolet", "Verde");
$auto2 = new Auto("Chevrolet", "Rojo");

// dos objetos “Auto” de la misma marca, mismo color y distinto precio.
$auto3 = new Auto("Fiat", "Negro", 3500);
$auto4 = new Auto("Fiat", "Negro", 5200);

// un objeto “Auto” utilizando la sobrecarga restante.
$auto5 = new Auto("Ford", "Gris", 4500, new DateTime("2000-01-01"));

// Utilizar el método “AgregarImpuesto” en los últimos tres objetos, agregando $ 1500 al atributo precio.
$auto3->AgregarImpuestos(1500);
$auto4->AgregarImpuestos(1500);
$auto5->AgregarImpuestos(1500);

// Obtener el importe sumado del primer objeto “Auto” más el segundo y mostrar el resultado obtenido.
Auto::Add($auto1, $auto2);

// Comparar el primer “Auto” con el segundo y quinto objeto e informar si son iguales o no.
echo "<br>";
echo "Los autos 1 y 2 " . ($auto1->Equals($auto2) ? "SI" : "NO") . " son iguales";
echo "<br>";
echo "Los autos 1 y 5 " . ($auto1->Equals($auto5) ? "SI" : "NO") . " son iguales";

// Utilizar el método de clase “MostrarAuto” para mostrar cada los objetos impares (1, 3, 5)
echo "<br>";
echo "<br>";
echo Auto::MostrarAuto($auto1);
echo "<br>";
echo Auto::MostrarAuto($auto3);
echo "<br>";
echo Auto::MostrarAuto($auto5);

?>