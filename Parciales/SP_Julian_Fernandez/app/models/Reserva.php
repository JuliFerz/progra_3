<?php

require_once './controllers/FileController.php';

class Reserva /* implements JsonSerializable */ {
    private string $_PATH = './images/ImagenesDeReservas/2023/';
    private int $_id;
    private string $_tipoCliente;
    private int $_nroCliente;
    private DateTime $_fechaEntrada;
    private DateTime $_fechaSalida;
    private string $_tipoHabitacion;
    private int $_importeTotal;
    private string $_modalidadPago;
    private string $_estado = 'activa';
    private ?string $_fotoReserva;
    private ?DateTime $_fechaBaja;


    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM reservas WHERE fecha_baja IS NULL");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Reserva');
    }

    // public static function obtenerClienteExistente($email)
    // {
    //     $objAccesoDatos = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM reservas
    //         WHERE email = :email 
    //             AND fecha_baja IS NULL");
    //     $consulta->bindValue(':email', $email, PDO::PARAM_STR);
    //     $consulta->execute();

    //     return $consulta->fetchObject('Reserva');
    // }

    // public static function obtenerTodosCSV()
    // {
    //     $objAccesoDatos = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM reservas");
    //     $consulta->execute();

    //     return $consulta->fetchAll(PDO::FETCH_ASSOC);
    // }

    public static function obtenerReserva($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM reservas 
            WHERE id = :id
                AND fecha_baja IS NULL");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchObject('Reserva');
    }

    public static function obtenerReservaPersonalizado($query)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta($query);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Reserva');
    }

    // public static function obtenerUsuarioDisponible($id)
    // {
    //     $objAccesoDatos = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDatos->prepararConsulta("SELECT *
    //         FROM reservas WHERE id = :id AND estado = 1;");
    //     $consulta->bindValue(':id', $id, PDO::PARAM_INT);
    //     $consulta->execute();
    //     return $consulta->fetchObject('Usuario');
    // }

    public function crearReserva()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO reservas 
                (tipo_cliente, nro_cliente, fecha_entrada, fecha_salida,
                tipo_habitacion, importe_total, modalidad_pago, estado)
            VALUES (:tipo_cliente, :nro_cliente, :fecha_entrada, :fecha_salida,
                :tipo_habitacion, :importe_total, :modalidad_pago, :estado)");
        
        $consulta->bindValue(':tipo_cliente', $this->_tipoCliente, PDO::PARAM_STR);
        $consulta->bindValue(':nro_cliente', $this->_nroCliente, PDO::PARAM_INT);
        $consulta->bindValue(':fecha_entrada', $this->_fechaEntrada->format('Y-m-d'));
        $consulta->bindValue(':fecha_salida', $this->_fechaSalida->format('Y-m-d'));
        $consulta->bindValue(':tipo_habitacion', $this->_tipoHabitacion, PDO::PARAM_STR);
        $consulta->bindValue(':importe_total', $this->_importeTotal, PDO::PARAM_INT);
        $consulta->bindValue(':modalidad_pago', $this->_modalidadPago, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->_estado, PDO::PARAM_STR);
        $consulta->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }

    public function modificarReserva($externo = false)
    {
        if ($externo){
            $this->_id = $this->{'id'};
            $this->_tipoCliente = $this->{'tipo_cliente'};
            $this->_nroCliente = $this->{'nro_cliente'};
            $this->_fechaEntrada = new DateTime(date('d-m-Y', 
                strtotime($this->{'fecha_entrada'})));
            $this->_fechaSalida = new DateTime(date('d-m-Y',
                strtotime($this->{'fecha_salida'})));
            $this->_tipoHabitacion = $this->{'tipo_habitacion'};
            $this->_importeTotal = $this->{'importe_total'};
            $this->_modalidadPago = $this->{'modalidad_pago'};
            $this->_estado = $this->{'estado'};
        }
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE reservas 
            SET tipo_cliente = :tipo_cliente,
                nro_cliente = :nro_cliente,
                fecha_entrada = :fecha_entrada,
                fecha_salida = :fecha_salida,
                tipo_habitacion = :tipo_habitacion,
                importe_total = :importe_total,
                modalidad_pago = :modalidad_pago,
                estado = :estado
            WHERE id = :id");
        $consulta->bindValue(':id', $this->_id, PDO::PARAM_INT);
        $consulta->bindValue(':tipo_cliente', $this->_tipoCliente, PDO::PARAM_STR);
        $consulta->bindValue(':nro_cliente', $this->_nroCliente, PDO::PARAM_INT);
        $consulta->bindValue(':fecha_entrada', $this->_fechaEntrada->format('Y-m-d'));
        $consulta->bindValue(':fecha_salida', $this->_fechaSalida->format('Y-m-d'));
        $consulta->bindValue(':tipo_habitacion', $this->_tipoHabitacion, PDO::PARAM_STR);
        $consulta->bindValue(':importe_total', $this->_importeTotal, PDO::PARAM_INT);
        $consulta->bindValue(':modalidad_pago', $this->_modalidadPago, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->_estado, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function borrarReserva($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE reservas 
            SET fecha_baja = :fecha_baja,
                estado = :estado
            WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':fecha_baja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->bindValue(':estado', 'cancelada');
        $consulta->execute();
    }

    // public static function DescargaUsuarios($usuarios){
    //     $fileController = new FileController('public/csv/');
    //     $file = $fileController->abrirArchivo('usuarios', 'csv');
    //     foreach ($usuarios as $usuario) {
    //         fputcsv($file, $usuario);
    //     }
    //     fclose($file);
    // }

    // public static function CargarUsuarios($archivo){
    //     $file = fopen($archivo, 'r');
    //     while (($data = fgetcsv($file)) !== FALSE) {
    //         $usuario = new Usuario();
    //         $usuario->setUsuario($data[0]);
    //         $usuario->setClave(password_hash($data[1], PASSWORD_DEFAULT));
    //         $usuario->setNombre($data[2]);
    //         $usuario->setApellido($data[3]);
    //         $usuario->setCorreo($data[4]);
    //         $usuario->setIdSector((int)$data[5]);
    //         $usuario->setPrioridad((int)$data[6]);
    //         $usuario->setEstado(1);
    //         $usuario->crearUsuario();
    //     }
    //     fclose($file);
    // }

    public function EstablecerFotoReserva($datosImg, $id)
    {
        $fileController = new FileController($this->_PATH);

        $nombreImagen = strtolower($this->_tipoCliente) . '_' 
            . (string) $this->_nroCliente 
            . '_' . (string) $this->_id;
        $fileController->setImage($datosImg, $nombreImagen);

        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE reservas 
            SET foto_reserva = :foto_reserva
            WHERE id = :id");
        $consulta->bindValue(':id', $this->_id, PDO::PARAM_INT);
        $consulta->bindValue(':foto_reserva', $nombreImagen . '.png');
        $consulta->execute();
        return $nombreImagen;
    }

    /* public function jsonSerialize()
    {
        return [
            'id' => $this->_id,
            'usuario' => $this->_usuario,
            'clave' => $this->_clave,
            'nombre' => $this->_nombre,
            'apellido' => $this->_apellido,
            'email' => $this->_email,
            'tipo_doc' => $this->_tipoDoc,
            'nro_doc' => $this->_nroDoc,
            'tipo_cliente' => $this->_tipoCliente,
            'pais' => $this->_pais,
            'ciudad' => $this->_ciudad,
            'telefono' => $this->_telefono,
            'modalidad_pago' => $this->_modalidadPago,
            'foto_usuario' => $this->_fotoUsuario,
            'fecha_baja' => $this->_fechaBaja
        ];
    } */

    //-- Getter
    public function getId(){
        return $this->_id;
    }
    public function getTipoCliente(){
        return $this->_tipoCliente;
    }
    public function getNroCliente(){
        return $this->_nroCliente;
    }
    public function getFechaEntrada(){
        return $this->_fechaEntrada;
    }
    public function getFechaSalida(){
        return $this->_fechaSalida;
    }
    public function getTipoHabitacion(){
        return $this->_tipoHabitacion;
    }
    public function getImporteTotal(){
        return $this->_importeTotal;
    }
    public function getModalidadPago(){
        return $this->_modalidadPago;
    }
    public function getEstado(){
        return $this->_estado;
    }
    public function getFotoReserva(){
        return $this->_fotoReserva;
    }
    public function getFechaBaja(){
        return $this->_fechaBaja;
    }

    //-- Setter
    public function setId($valor){
        $this->_id = $valor;
    }
    public function setTipoCliente($valor){
        $this->_tipoCliente = $valor;
    }
    public function setNroCliente($valor){
        $this->_nroCliente = $valor;
    }
    public function setFechaEntrada($valor){
        $this->_fechaEntrada = $valor;
    }
    public function setFechaSalida($valor){
        $this->_fechaSalida = $valor;
    }
    public function setTipoHabitacion($valor){
        $this->_tipoHabitacion = $valor;
    }
    public function setImporteTotal($valor){
        $this->_importeTotal = $valor;
    }
    public function setModalidadPago($valor){
        $this->_modalidadPago = $valor;
    }
    public function setEstado($valor){
        $this->_estado = $valor;
    }
    public function setFotoReserva($valor){
        $this->_fotoReserva = $valor;
    }
    public function setFechaBaja($valor){
        $this->_fechaBaja = $valor;
    }
    
}

?>