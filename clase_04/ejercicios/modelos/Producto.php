<?php
class Producto implements JsonSerializable
{
    private $_id;
    private $_ean;
    private $_nombre;
    private $_tipo;
    private $_stock;
    private $_precio;

    public function __construct($id, $ean, $nombre, $tipo, $stock, $precio)
    {
        if ($ean === 0 || (strlen((string) $ean) < 0 || strlen((string) $ean) > 6)) {
            throw new Exception('El EAN debe ser de 1 a 6 cifras');
        }
        $this->_id = $id;
        $this->_ean = $ean;
        $this->_nombre = $nombre;
        $this->_tipo = $tipo;
        $this->_stock = $stock;
        $this->_precio = $precio;
    }

    public static function AltaProducto($Producto)
    {
        $nombreArchivo = './productos.json';
        return $Producto->AltaJSON($nombreArchivo);
    }

    private function ExisteProducto($arrProds)
    {
        $res = false;
        foreach ($arrProds as $i => $producto) {
            if ($this->_id === $producto->_id) {
                $producto->_stock += $this->_stock;
                $res = true;
            }
        }
        return $res;
    }

    private function AltaJSON($nombreArchivo)
    {
        $res = 'Actualizado';
        $strContenido = file_get_contents($nombreArchivo);
        $jsonTotal = json_decode($strContenido);

        if (!$this->ExisteProducto($jsonTotal)) {
            $jsonTotal[] = $this;
            $res = 'Ingresado';
        }
        file_put_contents($nombreArchivo, json_encode($jsonTotal));

        return $res;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
?>