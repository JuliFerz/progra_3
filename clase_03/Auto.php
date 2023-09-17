<?php
class Auto{
    private $_color;
    private $_precio;
    private $_marca;
    private $_fecha;

    public function __construct($marca, $color, $precio = '', $fecha = ''){
        $this->_marca = $marca;
        $this->_color = $color;
        $this->_precio = $precio;
        $this->_fecha = $fecha ? $fecha : new DateTime();
    }

    public function AgregarImpuestos($plusPrecio){
        $this->_precio += $plusPrecio;
    }

    public static function MostrarAuto($Auto){
        echo '• Datos del auto' . '<br>';
        echo 'Color del auto: ' . $Auto->_color . '<br>';
        echo 'Precio del auto: ' . $Auto->_precio . '<br>';
        echo 'Marca del auto: ' . $Auto->_marca . '<br>';
        echo 'Fecha del auto: ' . $Auto->_fecha->format('Y-m-d H:i:s');
        echo '<br>';
    }

    public static function MostrarAutos($listaAutos){
        foreach ($listaAutos as $i => $datosAuto){
            echo '<b>Datos del auto N°' . $i+1 . ':</b>';
            echo '<br>';
            foreach($datosAuto as $dato){
                echo '• ' . $dato;
                echo '<br>';
            }
            echo '<br>';
        }
    }

    public function GetFields(){
        $list = [];
        foreach ($this as $value) {
            if ($value instanceof DateTime){
                $value = $value->format('Ymd_H:i:s');
            }
            array_push($list, $value);
        }
        return $list;
    }

    public function Equals($Auto){
        return $this->_marca === $Auto->_marca;
    }

    public static function Add($autoA, $autoB){
        $res = 0;
        if ($autoA->Equals($autoB) && $autoA->_color === $autoB->_color){
            $res = $autoA->_precio + $autoB->_precio;
        } else {
            echo 'Los autos no son iguales.';
        }
        return $res;
    }

    public static function AltaAuto($Auto){
        if (file_exists('./autos.csv')){
            $file = fopen('./autos.csv', 'a');
        } else {
            $file = fopen('./autos.csv', 'w');
        }
        $fieldList = [$Auto->GetFields()];
        foreach($fieldList as $field){
            fputcsv($file, $field);
        }
        fclose($file);
    }

    public static function LeerAutos($csvFile){
        if (file_exists($csvFile)){
            $file = fopen($csvFile, 'r');
            $autosLista = [];
            while (($data = fgetcsv($file)) !== FALSE) {
                array_push($autosLista, $data);
            }
            fclose($file);
            Auto::MostrarAutos($autosLista);
        }
        else {
            echo 'Archivo inexistente o inalcanzable.';
        }
    }
}
?>