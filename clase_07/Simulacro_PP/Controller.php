<?php

// require_once 'modelos/Producto.php';
require_once 'modelos/Pizza.php';


class Controller
{
    public function BuscarPizza($sabor, $tipo, $qty, $nombreArchivo)
    {
        $res = false;
        $arrJson = json_decode(file_get_contents($nombreArchivo));
        foreach ($arrJson as $i => $obj) {
            if ($sabor === $obj->_sabor && $tipo === $obj->_tipo) {
                if (($obj->_cantidad - $qty) >= 0) {
                    $obj->_cantidad -= $qty;
                    $res = $arrJson;
                } else {
                    throw new Exception('Se excedio la cantidad. No se puede comprar ' . $qty . ' unidades en la pizza \'' . $sabor . '\'.');
                }
            }
        }
        return $res;
    }

    public function DescontarStockPizza($pizza/* , $qty */){
        Pizza::ActualizarJson($pizza, './Pizza.json');
    }
    /* public function ControlarStock($eanId, $qty)
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
    } */
}

?>