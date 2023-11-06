<?php
class Pizza implements JsonSerializable
{
    private $_id;
    private $_sabor;
    private $_precio;
    private $_tipo;
    private $_cantidad;

    public function __construct($id, $sabor, $precio, $tipo, $cantidad)
    {
        if ($tipo !== 'molde' && $tipo !== 'piedra') {
            throw new Exception('Error, el tipo debe ser de molde o piedra');
        }
        $this->_id = $id;
        $this->_sabor = $sabor;
        $this->_precio = $precio;
        $this->_tipo = $tipo;
        $this->_cantidad = $cantidad;
    }

    public static function AltaPizza($Pizza)
    {
        $nombreArchivo = './Pizza.json';
        $Pizza->AltaJSON($nombreArchivo);
    }

    public static function AlreadyExist($getObj)
    {
        $response = false;
        $nombreArchivo = './Pizza.json';
        $arrJson = json_decode(file_get_contents($nombreArchivo));

        foreach ($arrJson as $i => $pizzaObj) {
            if ($pizzaObj->_sabor === $getObj['sabor'] && $pizzaObj->_tipo === $getObj['tipo']) {
                $pizzaObj->_precio = (int) $getObj['precio'];
                $pizzaObj->_cantidad += (int) $getObj['cantidad'];
                $response = true;
                break;
            }
        }
        Pizza::ActualizarJson($arrJson, $nombreArchivo);
        return $response;
    }

    public static function ActualizarJson($arrJson, $nombreArchivo)
    {
        $strJson = json_encode($arrJson);
        $file = fopen($nombreArchivo, 'w');
        fwrite($file, $strJson);
        fclose($file);
    }

    public static function BuscarPizza($sabor, $tipo)
    {
        $response = 'No se encontro la pizza ni por sabor ' . $sabor . ' ni por tipo ' . $tipo;
        $nombreArchivo = './Pizza.json';
        $arrJson = json_decode(file_get_contents($nombreArchivo));
        foreach ($arrJson as $i => $value) {
            if ($value->_sabor === $sabor && $value->_tipo === $tipo) {
                $response = 'Si Hay';
                break;
            } else if ($value->_sabor === $sabor && $value->_tipo !== $tipo) {
                $response = 'Se encontro una pizza por el sabor "' . $sabor . '", pero no por el tipo "' . $tipo . '"';
                break;
            } else if ($value->_sabor !== $sabor && $value->_tipo === $tipo) {
                $response = 'No se encontro una pizza por el sabor "' . $sabor . '", pero si por el tipo "' . $tipo . '"';
                break;
            }
        }
        return $response;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    private function AltaJSON($nombreArchivo)
    {
        $jsonTotal = null;
        $strTotal = '';
        $strContenido = file_get_contents($nombreArchivo);

        $file = fopen($nombreArchivo, 'w');
        $jsonTotal = json_decode($strContenido, true);
        $jsonTotal[] = $this;
        $strTotal = json_encode($jsonTotal);

        fwrite($file, $strTotal);
        fclose($file);
    }

}
?>