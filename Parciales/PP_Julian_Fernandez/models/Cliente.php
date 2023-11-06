<?php

require_once './controllers/FileController.php';
require_once './controllers/ClassController.php';

class Cliente implements JsonSerializable
{
    private $_id;
    private $_nombre;
    private $_apellido;
    private $_tipoDoc;
    private $_nroDoc;
    private $_email;
    private $_tipoCliente;
    private $_pais;
    private $_ciudad;
    private $_telefono;
    private $_modalidadPago;
    private $_fileController;
    private $_fotoUsuario;

    public function __construct($nombre, $apellido, $tipoDoc, $nroDoc, $email, $tipoCliente, $pais, $ciudad, $telefono, $modalidadPago = '', $infoImagen = [])
    {
        if (strtoupper($tipoCliente) != 'INDI' && strtoupper($tipoCliente) != 'CORPO') {
            throw new Exception('El cliente debe ser de tipo \'individual\' (INDI) o \'corporativo\' (CORPO)');
        }
        if (strtoupper($tipoDoc) !== 'DNI' && strtoupper($tipoDoc) !== 'LE' && strtoupper($tipoDoc) !== 'LC' && strtoupper($tipoDoc) !== 'PASAPORTE') {
            throw new Exception('El tipo de documento del cliente debe ser de tipo \'DNI\', \'LE\', \'LC\', o \'PASAPORTE\'');
        }
        $this->_id = ClassController::generateId('idsClientes');
        $this->_nombre = $nombre;
        $this->_apellido = $apellido;
        $this->_tipoDoc = strtoupper($tipoDoc);
        $this->_nroDoc = $nroDoc;
        $this->_email = $email;
        $this->_tipoCliente = strtoupper($tipoCliente);
        $this->_pais = $pais;
        $this->_ciudad = $ciudad;
        $this->_telefono = $telefono;
        $this->_modalidadPago = $modalidadPago ? $modalidadPago : 'Efectivo';
        $this->_fileController = new FileController('./data/hoteles.json');
        if (gettype($infoImagen) === 'array' && count($infoImagen) > 0) {
            $this->_fotoUsuario = $this->EstablecerFotoUsuario($infoImagen);
        } elseif (gettype($infoImagen) === 'string') {
            $this->_fotoUsuario = $infoImagen;
        }
    }

    public static function altaCliente($cliente)
    {
        $cliente->altaJSON();
    }

    private function EstablecerFotoUsuario($arrImagen)
    {
        $nombreImagen = (string) $this->_id . $this->_tipoCliente . '-' . $this->_tipoDoc;
        $this->_fileController->setImage($arrImagen, $nombreImagen, 'ImagenesDeClientes');
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
            'nombre' => $this->_nombre,
            'apellido' => $this->_apellido,
            'tipoDoc' => $this->_tipoDoc,
            'nroDoc' => $this->_nroDoc,
            'email' => $this->_email,
            'tipoCliente' => $this->_tipoCliente,
            'pais' => $this->_pais,
            'ciudad' => $this->_ciudad,
            'telefono' => $this->_telefono,
            'modalidadPago' => $this->_modalidadPago,
            'fotoUsuario' => $this->_fotoUsuario
        ];
    }

    public function getId()
    {
        return $this->_id;
    }
    public function getNombre()
    {
        return $this->_nombre;
    }
    public function getApellido()
    {
        return $this->_apellido;
    }
    public function getTipoDoc()
    {
        return $this->_tipoDoc;
    }
    public function getNroDoc()
    {
        return $this->_nroDoc;
    }
    public function getEmail()
    {
        return $this->_email;
    }
    public function getTipoCliente()
    {
        return $this->_tipoCliente;
    }
    public function getPais()
    {
        return $this->_pais;
    }
    public function getCiudad()
    {
        return $this->_ciudad;
    }
    public function getTelefono()
    {
        return $this->_telefono;
    }
}
?>