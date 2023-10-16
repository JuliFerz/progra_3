<?php

require_once 'modelos/Producto.php';
require_once 'modelos/Usuario.php';

class Controller
{
    public function BuscarEnJson($valor, $key, $nombreArchivo)
    {
        $res = false;
        $arrJson = json_decode(file_get_contents($nombreArchivo));
        foreach ($arrJson as $i => $obj) {
            if ($valor === $obj->{$key}) {
                $res = true;
            }
        }
        return $res;
    }

    public function ControlarStock($eanId, $qty)
    {
        $res = false;
        $arrJson = json_decode(file_get_contents('./productos.json'));
        foreach ($arrJson as $i => $producto) {
            if ($eanId === $producto->_ean) {
                if (($producto->_stock - $qty) >= 0) {
                    $res = true;
                }
            }
        }
        return $res;
    }
}

?>