<?php

require_once './controllers/FileController.php';

class Cliente /* implements JsonSerializable */ {
    private string $_PATH = './images/ImagenesDeClientes/2023/';
    private int $_id;
    private string $_usuario;
    private string $_clave;
    private string $_nombre;
    private string $_apellido;
    private string $_email;
    private string $_tipoDoc;
    private int $_nroDoc;
    private string $_tipoCliente;
    private string $_pais;
    private string $_ciudad;
    private int $_telefono;
    private string $_modalidadPago;
    private ?string $_fotoUsuario;
    // private ?FileController $_fileController;
    private ?DateTime $_fechaBaja;

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM clientes WHERE fecha_baja IS NULL");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Cliente');
    }

    public static function obtenerClienteExistente($email)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM clientes
            WHERE email = :email 
                AND fecha_baja IS NULL");
        $consulta->bindValue(':email', $email, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Cliente');
    }
    // public static function obtenerTodosCSV()
    // {
    //     $objAccesoDatos = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM clientes");
    //     $consulta->execute();

    //     return $consulta->fetchAll(PDO::FETCH_ASSOC);
    // }

    public static function obtenerCliente($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM clientes 
            WHERE id = :id
                AND fecha_baja IS NULL");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchObject('Cliente');
    }

    // public static function obtenerUsuarioDisponible($id)
    // {
    //     $objAccesoDatos = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDatos->prepararConsulta("SELECT *
    //         FROM clientes WHERE id = :id AND estado = 1;");
    //     $consulta->bindValue(':id', $id, PDO::PARAM_INT);
    //     $consulta->execute();
    //     return $consulta->fetchObject('Usuario');
    // }

    public function crearCliente()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO clientes 
                (usuario, clave, nombre, apellido, email, tipo_doc,
                nro_doc, tipo_cliente, pais, ciudad, 
                telefono, modalidad_pago)
            VALUES (:usuario, :clave, :nombre, :apellido, :email, :tipo_doc,
                :nro_doc, :tipo_cliente, :pais, :ciudad, 
                :telefono, :modalidad_pago)");
        $claveHash = password_hash($this->_clave, PASSWORD_DEFAULT);
        
        $consulta->bindValue(':usuario', $this->_usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->bindValue(':nombre', $this->_nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->_apellido, PDO::PARAM_STR);
        $consulta->bindValue(':email', $this->_email, PDO::PARAM_STR);
        $consulta->bindValue(':tipo_doc', $this->_tipoDoc, PDO::PARAM_STR);
        $consulta->bindValue(':nro_doc', $this->_nroDoc, PDO::PARAM_INT);
        $consulta->bindValue(':tipo_cliente', $this->_tipoCliente, PDO::PARAM_STR);
        $consulta->bindValue(':pais', $this->_pais, PDO::PARAM_STR);
        $consulta->bindValue(':ciudad', $this->_ciudad, PDO::PARAM_STR);
        $consulta->bindValue(':telefono', $this->_telefono, PDO::PARAM_INT);
        $consulta->bindValue(':modalidad_pago', $this->_modalidadPago, PDO::PARAM_STR);
        $consulta->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }

    public function modificarCliente($externo = false)
    {
        // if ($externo){
        //     $this->_id = $this->{'id'};
        //     $this->_usuario = $this->{'usuario'};
        //     $this->_clave = $this->{'clave'};
        //     $this->_nombre = $this->{'nombre'};
        //     $this->_apellido = $this->{'apellido'};
        //     $this->_correo = $this->{'correo'};
        //     $this->_idSector = $this->{'id_sector'};
        //     $this->_prioridad = $this->{'prioridad'};
        //     $this->_estado = $this->{'estado'};
        // }
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE clientes 
            SET usuario = :usuario,
                clave = :clave,
                nombre = :nombre,
                apellido = :apellido,
                email = :email,
                tipo_doc = :tipo_doc,
                nro_doc = :nro_doc,
                tipo_cliente = :tipo_cliente,
                pais = :pais,
                ciudad = :ciudad,
                telefono = :telefono,
                modalidad_pago = :modalidad_pago
            WHERE id = :id");
        $claveHash = password_hash($this->_clave, PASSWORD_DEFAULT);

        $consulta->bindValue(':usuario', $this->_usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->bindValue(':nombre', $this->_nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->_apellido, PDO::PARAM_STR);
        $consulta->bindValue(':email', $this->_email, PDO::PARAM_STR);
        $consulta->bindValue(':tipo_doc', $this->_tipoDoc, PDO::PARAM_STR);
        $consulta->bindValue(':nro_doc', $this->_nroDoc, PDO::PARAM_INT);
        $consulta->bindValue(':tipo_cliente', $this->_tipoCliente, PDO::PARAM_STR);
        $consulta->bindValue(':pais', $this->_pais, PDO::PARAM_STR);
        $consulta->bindValue(':ciudad', $this->_ciudad, PDO::PARAM_STR);
        $consulta->bindValue(':telefono', $this->_telefono, PDO::PARAM_INT);
        $consulta->bindValue(':modalidad_pago', $this->_modalidadPago, PDO::PARAM_STR);
        $consulta->bindValue(':id', $this->_id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarUsuario($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE clientes SET fecha_baja = :fecha_baja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':fecha_baja', date_format($fecha, 'Y-m-d H:i:s'));
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

    public function EstablecerFotoCliente($datosImg, $id)
    {
        $fileController = new FileController($this->_PATH);

        $nombreImagen = (string) $this->_id 
            . $this->_tipoCliente . '-' . $this->_tipoDoc;
        $fileController->setImage($datosImg, $nombreImagen);

        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE clientes 
            SET foto_usuario = :foto_usuario
            WHERE id = :id");
        $consulta->bindValue(':id', $this->_id, PDO::PARAM_INT);
        $consulta->bindValue(':foto_usuario', $nombreImagen . '.png');
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
    public function getUsuario(){
        return $this->_usuario;
    }
    public function getClave(){
        return $this->_clave;
    }
    public function getNombre(){
        return $this->_nombre;
    }
    public function getApellido(){
        return $this->_apellido;
    }
    public function getEmail(){
        return $this->_email;
    }
    public function getTipoDoc(){
        return $this->_tipoDoc;
    }
    public function getNroDoc(){
        return $this->_nroDoc;
    }
    public function getTipoCliente(){
        return $this->_tipoCliente;
    }
    public function getPais(){
        return $this->_pais;
    }
    public function getCiudad(){
        return $this->_ciudad;
    }
    public function getTelefono(){
        return $this->_telefono;
    }
    public function getModalidadPago(){
        return $this->_modalidadPago;
    }
    public function getFotoUsuario(){
        return $this->_fotoUsuario;
    }
    // public function getFileController(){
    //     return $this->_fileController;
    // }
    public function getFechaBaja(){
        return $this->_fechaBaja;
    }

    //-- Setter
    public function setId($valor){
        $this->_id = $valor;
    }
    public function setUsuario($valor){
        $this->_usuario = $valor;
    }
    public function setClave($valor){
        $this->_clave = $valor;
    }
    public function setNombre($valor){
        $this->_nombre = $valor;
    }
    public function setApellido($valor){
        $this->_apellido = $valor;
    }
    public function setEmail($valor){
        $this->_email = $valor;
    }
    public function setTipoDoc($valor){
        $this->_tipoDoc = $valor;
    }
    public function setNroDoc($valor){
        $this->_nroDoc = $valor;
    }
    public function setTipoCliente($valor){
        $this->_tipoCliente = $valor;
    }
    public function setPais($valor){
        $this->_pais = $valor;
    }
    public function setCiudad($valor){
        $this->_ciudad = $valor;
    }
    public function setTelefono($valor){
        $this->_telefono = $valor;
    }
    public function setModalidadPago($valor){
        $this->_modalidadPago = $valor;
    }
    public function setFotoUsuario($valor){
        $this->_fotoUsuario = $valor;
    }
    // public function setFileController($valor){
    //     $this->_fileController = $valor;
    // }
    public function setFechaBaja($valor){
        $this->_fechaBaja = $valor;
    }
    
}

?>