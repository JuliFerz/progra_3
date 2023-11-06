<?php

require_once './controllers/FileController.php';
require_once './controllers/ClassController.php';

class Reserva implements JsonSerializable
{
    private $_id;
    private $_tipoCliente;
    private $_nroCliente;
    private $_fechaEntrada;
    private $_fechaSalida;
    private $_tipoHabitacion;
    private $_importeTotal;
    private $_modalidadPago;
    private $_estado;
    private $_fileController;
    private $_fotoReserva;

    public function __construct($tipoCliente, $nroCliente, $fechaEntrada, $fechaSalida, $tipoHabitacion, $importeTotal, $modalidadPago, $infoImagen = [])
    {
        if ($tipoHabitacion != 'simple' && $tipoHabitacion != 'doble' && $tipoHabitacion != 'suite') {
            throw new Exception('El tipo de habitacion debe ser de tipo \'simple\', \'doble\' o \'suite\'');
        }
        $this->_id = ClassController::generateId('idsReservas');
        $this->_tipoCliente = $tipoCliente;
        $this->_nroCliente = $nroCliente;
        $this->_fechaEntrada = $fechaEntrada;
        $this->_fechaSalida = $fechaSalida;
        $this->_tipoHabitacion = $tipoHabitacion;
        $this->_importeTotal = $importeTotal;
        $this->_modalidadPago = $modalidadPago;
        $this->_estado = 'activa';
        $this->_fileController = new FileController('./data/reservas.json');
        if (gettype($infoImagen) === 'array' && count($infoImagen) > 0) {
            $this->_fotoReserva = $this->EstablecerFotoReserva($infoImagen);
        } elseif (gettype($infoImagen) === 'string') {
            $this->_fotoReserva = $infoImagen;
        }
    }

    public static function altaReserva($reserva)
    {
        $reserva->altaJSON();
    }

    private function EstablecerFotoReserva($arrImagen)
    {
        $nombreImagen = strtolower($this->_tipoCliente) . '_' . (string) $this->_nroCliente . '_' . (string) $this->_id;
        $this->_fileController->setImage($arrImagen, $nombreImagen, 'ImagenesDeReservas');
        return $nombreImagen;
    }

    private function altaJSON()
    {
        $jsonTotal = $this->_fileController->read();
        $jsonTotal[] = $this;
        $this->_fileController->write($jsonTotal);
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->_id,
            'estado' => $this->_estado,
            'tipoCliente' => $this->_tipoCliente,
            'nroCliente' => $this->_nroCliente,
            'fechaEntrada' => $this->_fechaEntrada,
            'fechaSalida' => $this->_fechaSalida,
            'tipoHabitacion' => $this->_tipoHabitacion,
            'importeTotal' => $this->_importeTotal,
            'modalidadPago' => $this->_modalidadPago,
            'fotoReserva' => $this->_fotoReserva,
        ];
    }

}
?>