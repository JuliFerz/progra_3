<?php
class Usuario {
    private $_nombre;
    private $_clave;
    private $_mail;

    public function __construct($nombre, $clave, $mail){
        $this->_nombre = $nombre;
        $this->_clave = $clave;
        $this->_mail = $mail;
    }

    public static function AltaUsuario($Usuario){
        if (file_exists('./usuarios.csv')){
            $file = fopen('./usuarios.csv', 'a');
        } else {
            $file = fopen('./usuarios.csv', 'w');
        }
        $fieldList = [$Usuario->GetFields()];
        foreach($fieldList as $field){
            fputcsv($file, $field);
        }
        fclose($file);
    }

    public static function MostrarUsuario($Usuario){
        echo '<ul>';
        foreach($Usuario as $dato){
            echo '<li>' . $dato . '</li>';
        }
        echo '</ul>';
    }

    public static function LeerUsuarios($csvFile){
        if (file_exists($csvFile)){
            $file = fopen($csvFile, 'r');
            while (($data = fgetcsv($file)) !== FALSE) {
                $usuario = new Usuario($data[0], $data[1], $data[2]);
                Usuario::MostrarUsuario($usuario);
            }
            fclose($file);
        }
        else {
            echo 'Archivo inexistente o inalcanzable.';
        }
    }

    public static function BuscarUsuario($archivo, $clave, $mail){
        $file = fopen($archivo, 'r');
        $resultado = 'Usuario no registrado';
        while (($data = fgetcsv($file)) !== FALSE) {
            if ($clave === $data[1] && $mail === $data[2]){
                $resultado = 'Verificado';
                break;
            } else if ($clave != $data[1] && $mail === $data[2]){
                $resultado = 'Error en los datos';
                break;
            }
        }
        fclose($file);
        return $resultado;
    }

    private function GetFields(){
        $list = [];
        foreach ($this as $value) {
            array_push($list, $value);
        }
        return $list;
    }
}

?>