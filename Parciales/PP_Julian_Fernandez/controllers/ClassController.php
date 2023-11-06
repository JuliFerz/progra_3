<?php

require_once 'FileController.php';

class ClassController
{
    static public function validateInputNumber($strNumber)
    {
        if (!preg_match('/^[0-9]+$/', $strNumber)) {
            throw new Exception('Se encontro un caracter no numerico');
        }
    }

    static public function validateInputDate($strDate)
    {
        if (!preg_match('/^[0-9]{2}-[0-9]{2}-[0-9]{4}$/', $strDate)) {
            throw new Exception("Los formatos fecha no coinciden. Formato valido: 'dd-mm-yyyy'");
        }
    }

    static public function generateId($archivo)
    {
        $fileController = new FileController('./data/' . $archivo . '.json');
        $objIds = $fileController->read();
        $newId = 0;
        if ($objIds != null) {
            $newId = $objIds[count($objIds) - 1] + 1;
            if ($newId > $fileController->getMaxId()) {
                throw new Exception('Se supero el limite de clientes permitidos a crear.');
            }
            $objIds[] = $newId;
        } else {
            $newId = $fileController->getMinId();
            $objIds[] = $newId;
        }

        $fileController->write($objIds);
        return $newId;
    }

    public static function BuscarEnJson($valor, $key, $archivo)
    {
        $fileController = new FileController('./data/' . $archivo . '.json');
        $res = false;
        $arrJson = $fileController->readAsObject();
        if ($arrJson) {
            foreach ($arrJson as $i => $obj) {
                if ($valor === $obj->{$key}) {
                    $res = true;
                }
            }
        }
        return $res;
    }

    public static function BuscarClienteJson($valores)
    {
        $fileController = new FileController('./data/hoteles.json');
        $res = false;
        $arrJson = $fileController->readAsObject();

        $arrBool = [];
        if ($arrJson) {
            foreach ($arrJson as $i => $obj) {
                if ($valores['id'] === $obj->{'id'} && $valores['tipoCliente'] === $obj->{'tipoCliente'}) {
                    $res = $obj;
                    break;
                } else if ($valores['id'] === $obj->{'id'} && !($valores['tipoCliente'] === $obj->{'tipoCliente'})) {
                    throw new Exception('Tipo de cliente incorrecto');
                } else {
                    array_push($arrBool, $i);
                }
            }
            if (count($arrBool) === count($arrJson)) {
                throw new Exception('No existe un cliente con esa combinacion');
            }
        }
        return $res;
    }

    public static function ActualizarClienteJson($datosCliente)
    {
        $fileController = new FileController('./data/hoteles.json');
        $objJson = $fileController->readAsObject();
        foreach ($objJson as $i => $obj) {
            if ((int) $datosCliente['nroCliente'] === $obj->{'id'} && $datosCliente['tipoCliente'] === $obj->{'tipoCliente'}) {
                // No se actualiza ID y tipoCliente
                $obj->{'nombre'} = $datosCliente['nombre'];
                $obj->{'apellido'} = $datosCliente['apellido'];
                $obj->{'tipoDoc'} = $datosCliente['tipoDoc'];
                $obj->{'nroDoc'} = (int) $datosCliente['nroDoc'];
                $obj->{'email'} = $datosCliente['email'];
                $obj->{'pais'} = $datosCliente['pais'];
                $obj->{'ciudad'} = $datosCliente['ciudad'];
                $obj->{'telefono'} = (int) $datosCliente['telefono'];
            }
        }
        $fileController->write($objJson);
    }

    public static function ObtenerTotalReservas($fecha = '')
    {
        $fecha = $fecha ? $fecha : date('d-m-Y', strtotime($fecha . "- 1 days"));
        $totalReservas = 0;
        $fileController = new FileController('./data/reservas.json');
        $objJson = $fileController->read();
        $arrReservas = array_filter($objJson, function ($obj) use ($fecha) {
            return $obj['fechaEntrada'] === $fecha;
        });

        if ($arrReservas) {
            $totalReservas = array_reduce($arrReservas, function ($x, $el) {
                return $x + $el['importeTotal'];
            });
        }
        return $totalReservas;
    }

    public static function BuscarReservaPorCliente($idCliente)
    {
        $fileController = new FileController('./data/reservas.json');
        $objJson = $fileController->read();
        $arrReservas = array_filter($objJson, function ($obj) use ($idCliente) {
            return $obj['nroCliente'] === $idCliente;
        });
        return $arrReservas;
    }

    public static function BuscarReservasPorFechas($fechaDesde, $fechaHasta)
    {
        $fileController = new FileController('./data/reservas.json');
        $objJson = $fileController->read();
        $arrReservas = array_filter($objJson, function ($obj) use ($fechaDesde, $fechaHasta) {
            $fechaDesde = DateTime::createFromFormat('d-m-Y', $fechaDesde);
            $fechaHasta = DateTime::createFromFormat('d-m-Y', $fechaHasta);
            $fechaObj = DateTime::createFromFormat('d-m-Y', $obj['fechaEntrada']);
            return $fechaObj >= $fechaDesde && $fechaObj <= $fechaHasta;
        });
        uasort($arrReservas, function ($r1, $r2) {
            $fecha1 = DateTime::createFromFormat('d-m-Y', $r1['fechaEntrada']);
            $fecha2 = DateTime::createFromFormat('d-m-Y', $r2['fechaEntrada']);
            return $fecha1 <=> $fecha2;
        });
        return $arrReservas;
    }

    public static function BuscarReservasPorHabitacion($habitacion)
    {
        $fileController = new FileController('./data/reservas.json');
        $objJson = $fileController->read();
        $arrReservas = array_filter($objJson, function ($obj) use ($habitacion) {
            return $obj['tipoHabitacion'] === $habitacion;
        });
        return $arrReservas;
    }

    public static function CancelarReserva($idReserva)
    {
        $fileController = new FileController('./data/reservas.json');
        $objJson = $fileController->readAsObject();
        foreach ($objJson as $i => $obj) {
            if ($obj->{'id'} === $idReserva) {
                $obj->{'estado'} = 'cancelada';
                break;
            }
        }
        $fileController->write($objJson);
    }

    public static function AjustarReserva($idReserva, $importe)
    {
        $res = [];
        $fileController = new FileController('./data/reservas.json');
        $objJson = $fileController->readAsObject();
        foreach ($objJson as $i => $obj) {
            if ($obj->{'id'} === $idReserva) {
                $res = ['importePrevio' => $obj->{'importeTotal'}, 'importeNuevo' => $importe];
                $obj->{'importeTotal'} = $importe;
                $obj->{'estado'} = 'activa'; // este es el cambio de estado?
                break;
            }
        }
        $fileController->write($objJson);

        return $res;
    }

    public static function GrabarAjusteReserva($idReserva, $datosReserva, $motivo)
    {
        $res = [];
        $fileController = new FileController('./data/ajustes.json');
        $objJson = $fileController->readAsObject();
        $objJson[] = [
            'idReserva' => $idReserva,
            'importePrevio' => $datosReserva['importePrevio'],
            'importeNuevo' => $datosReserva['importeNuevo'],
            'motivo' => $motivo
        ];
        $fileController->write($objJson);
        return $res;
    }

    public static function BorrarCliente($data){
        $fileController = new FileController('C:/xampp/htdocs/progra_3/Parciales/PP_Julian_Fernandez/ImagenesBackupClientes/2023/');
        $pathOrigen = 'C:/xampp/htdocs/progra_3/Parciales/PP_Julian_Fernandez/ImagenesDeClientes/2023/' . $data->{'fotoUsuario'} . '.png';
        
        $reservas = ClassController::BuscarReservaPorCliente($data->{'id'});
        foreach ($reservas as $i => $obj){
            ClassController::CancelarReserva($obj['id']);
        }
        $fileController->moveImage($data->{'fotoUsuario'}, $pathOrigen);
    }

    public static function ObtenerTotalReservasPorClienteYFecha($tipoCliente, $fecha = '')
    {
        $fecha = $fecha ? $fecha : date('d-m-Y', strtotime($fecha . "- 1 days"));
        $totalReservas = 0;
        $fileController = new FileController('./data/reservas.json');
        $objJson = $fileController->read();
        $arrReservas = array_filter($objJson, function ($obj) use ($fecha, $tipoCliente) {
            return $obj['fechaEntrada'] === $fecha && $obj['estado'] === 'cancelada' && $obj['tipoCliente'] === $tipoCliente;
        });

        if ($arrReservas) {
            $totalReservas = array_reduce($arrReservas, function ($x, $el) {
                return $x + $el['importeTotal'];
            });
        }
        return $totalReservas;
    }

    public static function ObtenerReservasCanceladasPorCliente($idCliente)
    {
        $totalReservas = 0;
        $fileController = new FileController('./data/reservas.json');
        $objJson = $fileController->read();
        $arrReservas = array_filter($objJson, function ($obj) use ($idCliente) {
            return $obj['nroCliente'] === $idCliente && $obj['estado'] === 'cancelada';
        });
        return $arrReservas;
    }

    public static function BuscarReservasCanceladasPorFechas($fechaDesde, $fechaHasta)
    {
        $fileController = new FileController('./data/reservas.json');
        $objJson = $fileController->read();
        $arrReservas = array_filter($objJson, function ($obj) use ($fechaDesde, $fechaHasta) {
            $fechaDesde = DateTime::createFromFormat('d-m-Y', $fechaDesde);
            $fechaHasta = DateTime::createFromFormat('d-m-Y', $fechaHasta);
            $fechaObj = DateTime::createFromFormat('d-m-Y', $obj['fechaEntrada']);
            return $fechaObj >= $fechaDesde && $fechaObj <= $fechaHasta && $obj['estado'] === 'cancelada';
        });
        uasort($arrReservas, function ($r1, $r2) {
            $fecha1 = DateTime::createFromFormat('d-m-Y', $r1['fechaEntrada']);
            $fecha2 = DateTime::createFromFormat('d-m-Y', $r2['fechaEntrada']);
            return $fecha1 <=> $fecha2;
        });
        return $arrReservas;
    }
    public static function ObtenerReservasCanceladasPorTipoCliente($tipoCliente)
    {
        $totalReservas = 0;
        $fileController = new FileController('./data/reservas.json');
        $objJson = $fileController->read();
        $arrReservas = array_filter($objJson, function ($obj) use ($tipoCliente) {
            return $obj['estado'] === 'cancelada' && $obj['tipoCliente'] === $tipoCliente;
        });
        return $arrReservas;
    }
    public static function ObtenerReservasPorTipoModalidad($modalidad)
    {
        $fileController = new FileController('./data/reservas.json');
        $objJson = $fileController->read();
        $arrReservas = array_filter($objJson, function ($obj) use ($modalidad) {
            return $obj['modalidadPago'] === $modalidad;
        });
        return $arrReservas;
    }
}
?>