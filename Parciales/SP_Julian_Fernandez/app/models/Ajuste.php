<?php

class Ajuste{
    private int $_id;
    private int $_idReserva;
    private int $_importePrevio;
    private int $_importeNuevo;
    private string $_motivo;

    public function crearAjuste()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO ajustes 
                (id_reserva, importe_previo, importe_nuevo, motivo)
            VALUES (:id_reserva, :importe_previo, :importe_nuevo, :motivo)");
        
        $consulta->bindValue(':id_reserva', $this->_idReserva, PDO::PARAM_INT);
        $consulta->bindValue(':importe_previo', $this->_importePrevio, PDO::PARAM_INT);
        $consulta->bindValue(':importe_nuevo', $this->_importeNuevo, PDO::PARAM_INT);
        $consulta->bindValue(':motivo', $this->_motivo, PDO::PARAM_STR);
        $consulta->execute();
    }

    //-- Getter
    public function getId(){
        return $this->_id;
    }
    public function getIdReserva(){
        return $this->_idReserva;
    }
    public function getImportePrevio(){
        return $this->_importePrevio;
    }
    public function getImporteNuevo(){
        return $this->_importeNuevo;
    }
    public function getMotivo(){
        return $this->_motivo;
    }

    //-- Setter
    public function setId($valor){
        $this->_id = $valor;
    }
    public function setIdReserva($valor){
        $this->_idReserva = $valor;
    }
    public function setImportePrevio($valor){
        $this->_importePrevio = $valor;
    }
    public function setImporteNuevo($valor){
        $this->_importeNuevo = $valor;
    }
    public function setMotivo($valor){
        $this->_motivo = $valor;
    }
    
}

?>