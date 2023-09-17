<?php
/*
    Ejercicio: 18
    Enunciado: 
        Crear la clase Garage que posea como atributos privados:
            _razonSocial (String)
            _precioPorHora (Double)
            _autos (Autos[], reutilizar la clase Auto del ejercicio anterior)
        Realizar un constructor capaz de poder instanciar objetos pasándole como parámetros: 
            i. La razón social.
            ii. La razón social, y el precio por hora.

        Realizar un método de instancia llamado “MostrarGarage”, que no recibirá parámetros y que mostrará todos los atributos del objeto.
        Crear el método de instancia “Equals” que permita comparar al objeto de tipo Garaje con un objeto de tipo Auto. Sólo devolverá TRUE si el auto está en el garaje.
        Crear el método de instancia “Add” para que permita sumar un objeto “Auto” al “Garage” (sólo si el auto no está en el garaje, de lo contrario informarlo).
        Ejemplo: $miGarage->Add($autoUno);

        Crear el método de instancia “Remove” para que permita quitar un objeto “Auto” del “Garage” (sólo si el auto está en el garaje, de lo contrario informarlo).
        Ejemplo: $miGarage->Remove($autoUno);
        En testGarage.php, crear autos y un garage. Probar el buen funcionamiento de todos los métodos.

    Alumno: Julian Fernandez
*/
include "ejercicio_17.php"; // Es la clase de Auto

class Garage {

    private $_razonSocial;
    private $_precioPorHora;
    private $_autos = [];

    public function __construct($razonSocial, $precioPorHora = 0){
        $this->_razonSocial = $razonSocial;
        $this->_precioPorHora = $precioPorHora;
    }

    public function MostrarGarage(){
        echo "<br>";
        echo "• Datos del garage" . "<br>";
        echo "Razon social del garage:" . $this->_razonSocial . "<br>";
        echo "Precio del garage:" . $this->_precioPorHora . "<br>";
        echo "  Autos del garage: " . count($this->_autos) . "<br>";
        for ($i = 0; $i < count($this->_autos); $i++) {
            echo "<b> Auto " . $i+1 . ":</b>";
            echo Auto::MostrarAuto($this->_autos[$i]) . "<br>";
        }
        echo "<br>";
    }

    public function Equals($auto){
        return in_array($auto, $this->_autos);
    }

    public function Add($auto){
        if (!$this->Equals($auto)){
            array_push($this->_autos, $auto);
        } else {
            echo "El auto ya se encuentra en el garage";
        }
    }

    public function Remove($auto){
        $indexAuto = array_search($auto, $this->_autos);
        /* if ($this->Equals($auto)){
            foreach ($this->_autos as $i => $value) {
                if ($auto === $value){
                    unset($this->_autos[$i]);
                    // PHP no re-organiza indices al quitar uno. Reorganizar indices:
                    $this->_autos = array_values($this->_autos);
                    break;
                }
            }
        } */
        if ($indexAuto){
            unset($this->_autos[$indexAuto]);
            $this->_autos = array_values($this->_autos);
        }
        else {
            echo "El auto no se encuentra en el garage";
        }
        /*
        También se puede hacer con array_filter() -> Retorna un nuevo array con las coincidencias
        */
    }

}
?>