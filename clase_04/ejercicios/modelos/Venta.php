<?php
class Venta implements JsonSerializable
{
    private $_id;
    private $_ean;
    private $_idUsuario;
    private $_cantidad;

    public function __construct($id, $ean, $idUsuario, $cantidad)
    {
        $this->_id = $id;
        $this->_ean = $ean;
        $this->_idUsuario = $idUsuario;
        $this->_cantidad = $cantidad;
    }

    public static function AltaVenta($venta)
    {
        $nombreArchivo = './ventas.json';
        return $venta->AltaJSON($nombreArchivo);
    }

    private function AltaJSON($nombreArchivo)
    {
        $arrJson = json_decode(file_get_contents($nombreArchivo));
        $arrJson[] = $this;
        file_put_contents($nombreArchivo, json_encode($arrJson));
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
?>