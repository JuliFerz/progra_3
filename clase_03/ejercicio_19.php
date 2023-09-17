<?php
/*
    Ejercicio: 19
    Enunciado: Realizar una clase llamada “Auto” que posea los siguientes atributos privados: 
        _color (String)
        _precio (Double)
        _marca (String)
        _fecha (DateTime)
    Realizar un constructor capaz de poder instanciar objetos pasándole como parámetros: 
        i. La marca y el color.
        ii. La marca, color y el precio.
        iii. La marca, color, precio y fecha.
    Realizar un método de instancia llamado “AgregarImpuestos”, que recibirá un doble por parámetro y que se sumará al precio del objeto.
    Realizar un método de clase llamado “MostrarAuto”, que recibirá un objeto de tipo “Auto” por parámetro y que mostrará todos los atributos de dicho objeto.
    Crear el método de instancia “Equals” que permita comparar dos objetos de tipo “Auto”. Sólo devolverá TRUE si ambos “Autos” son de la misma marca.
    Crear un método de clase, llamado “Add” que permita sumar dos objetos “Auto” (sólo si son de la misma marca, y del mismo color, de lo contrario informarlo) y que retorne un Double con la suma de los precios o cero si no se pudo realizar la operación.
        Ejemplo: $importeDouble = Auto::Add($autoUno, $autoDos);
    Crear un método de clase para poder hacer el alta de un Auto, guardando los datos en un archivo autos.csv.
    Hacer los métodos necesarios en la clase Auto para poder leer el listado desde el archivo autos.csv
    Se deben cargar los datos en un array de autos.

    En testAuto.php:
        ● Crear dos objetos “Auto” de la misma marca y distinto color.
        ● Crear dos objetos “Auto” de la misma marca, mismo color y distinto precio.
        ● Crear un objeto “Auto” utilizando la sobrecarga restante.
        ● Utilizar el método “AgregarImpuesto” en los últimos tres objetos, agregando $ 1500 al atributo precio.
        ● Obtener el importe sumado del primer objeto “Auto” más el segundo y mostrar el resultado obtenido.
        ● Comparar el primer “Auto” con el segundo y quinto objeto e informar si son iguales o no.
        ● Utilizar el método de clase “MostrarAuto” para mostrar cada los objetos impares (1, 3, 5)

    Alumno: Julian Fernandez
*/
include 'Auto.php';


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

/* // Obtener el importe sumado del primer objeto “Auto” más el segundo y mostrar el resultado obtenido.
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
echo Auto::MostrarAuto($auto5); */

// Auto::AltaAuto($auto5);

Auto::LeerAutos('./autos.csv');


?>