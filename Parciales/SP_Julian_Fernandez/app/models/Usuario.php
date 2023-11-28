<?php

class Usuario {
    private int $_id;
    private string $_usuario;
    private string $_clave;
    private string $_rol;

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuarios");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public function crearUsuario()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios 
                (usuario, clave, rol)
            VALUES (:usuario, :clave, :rol)");
        $claveHash = password_hash($this->_clave, PASSWORD_DEFAULT);
        
        $consulta->bindValue(':usuario', $this->_usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->bindValue(':rol', $this->_rol, PDO::PARAM_STR);
        $consulta->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }

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
    public function getRol(){
        return $this->_rol;
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
    public function setRol($valor){
        $this->_rol = $valor;
    }
}
?>