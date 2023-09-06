<?php
/*
    -> Del ejercicio 18
*/
include "ejercicio_17.php";
include "ejercicio_18.php";

$auto1 = new Auto("Chevrolet", "Verde");
$auto2 = new Auto("Chevrolet", "Rojo");
$auto3 = new Auto("Fiat", "Negro", 3500);
$auto4 = new Auto("Fiat", "Negro", 5200);
$auto5 = new Auto("Ford", "Gris", 4500, new DateTime("2000-01-01"));
$garage = new Garage("La Paulina", 15);

$garage->MostrarGarage();

echo "<h4>/////////////////////</h4>";

$garage->Add($auto1);
$garage->Add($auto3);
$garage->Add($auto5);
$garage->MostrarGarage();

echo "<h4>/////////////////////</h4>";

$garage->Remove($auto3);
$garage->MostrarGarage();

echo "<h4>/////////////////////</h4>";

$garage->Remove($auto3);

?>