<?php
/*
    Ejercicio: 17
    Enunciado: Realizar una clase llamada “Auto” que posea los siguientes atributos privados: 
            _color (String)
            _precio (Double)
            _marca (String).
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
class Auto{
    private $_color;
    private $_precio;
    private $_marca;
    private $_fecha;

    public function __construct($marca, $color, $precio = "", $fecha = ""){
        $this->_marca = $marca;
        $this->_color = $color;
        $this->_precio = $precio;
        $this->_fecha = $fecha ? $fecha : new DateTime();
    }

    public function AgregarImpuestos($plusPrecio){
        $this->_precio += $plusPrecio;
    }

    public static function MostrarAuto($Auto){
        echo "<br>";
        echo "• Datos del auto" . "<br>";
        echo "Color del auto: " . $Auto->_color . "<br>";
        echo "Precio del auto: " . $Auto->_precio . "<br>";
        echo "Marca del auto: " . $Auto->_marca . "<br>";
        echo "Fecha del auto: " . $Auto->_fecha->format('Y-m-d H:i:s');
    }

    public function Equals($Auto){
        return $this->_marca === $Auto->_marca;
    }

    public static function Add($autoA, $autoB){
        $res = 0;
        if ($autoA->Equals($autoB) && $autoA->_color === $autoB->_color){
            $res = $autoA->_precio + $autoB->_precio;
        } else {
            echo "Los autos no son iguales. Distintas marcas";
        }
        return $res;
    }
}
?>