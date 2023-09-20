<?php
include 'Auto.php';

class Garage {

    private $_razonSocial;
    private $_precioPorHora;
    private $_autos = [];

    public function __construct($razonSocial, $precioPorHora = 0){
        $this->_razonSocial = $razonSocial;
        $this->_precioPorHora = $precioPorHora;
    }

    public function MostrarGarage(){
        echo '<br>';
        echo '• Datos del garage' . '<br>';
        echo 'Razon social del garage:' . $this->_razonSocial . '<br>';
        echo 'Precio del garage:' . $this->_precioPorHora . '<br>';
        echo 'Autos del garage: ' . count($this->_autos) . '<br>';
        for ($i = 0; $i < count($this->_autos); $i++) {
            echo '<b> Auto ' . $i+1 . ':</b>';
            echo Auto::MostrarAuto($this->_autos[$i]) . '<br>';
        }
        echo '<br>';
    }


    public static function MostrarGarages($listaGarages){
        foreach ($listaGarages as $i => $datosGarage){
            echo '<b>Datos del garage N°' . $i+1 . ':</b>';
            echo '<br>';
            foreach($datosGarage as $dato){
                echo '• ' . $dato;
                echo '<br>';
            }
            echo '<br>';
        }
    }
    public function GetFields(){
        $list = [];
        foreach ($this as $value) {
            if (is_array($value))
                $value = count($value);
            array_push($list, $value);
        }
        return $list;
    }

    public function Equals($auto){
        return in_array($auto, $this->_autos);
    }

    public function Add($auto){
        if (!$this->Equals($auto)){
            array_push($this->_autos, $auto);
        } else {
            echo 'El auto ya se encuentra en el garage';
        }
    }

    public function Remove($auto){
        $indexAuto = array_search($auto, $this->_autos);
        if ($indexAuto){
            unset($this->_autos[$indexAuto]);
            $this->_autos = array_values($this->_autos);
        } else {
            echo 'El auto no se encuentra en el garage';
        }
    }

    public static function AltaGarage($Garage){
        if (file_exists('./garages.csv')){
            $file = fopen('./garages.csv', 'a');
        } else {
            $file = fopen('./garages.csv', 'w');
        }
        $fieldList = [$Garage->GetFields()];
        foreach($fieldList as $field){
            fputcsv($file, $field);
        }
        fclose($file);
    }

    public static function LeerGarages($csvFile){
        if (file_exists($csvFile)){
            $file = fopen($csvFile, 'r');
            $garagesLista = [];
            while (($data = fgetcsv($file)) !== FALSE) {
                array_push($garagesLista, $data);
            }
            fclose($file);
            Garage::MostrarGarages($garagesLista);
        }
        else {
            echo 'Archivo inexistente o inalcanzable.';
        }
    }

}
?>