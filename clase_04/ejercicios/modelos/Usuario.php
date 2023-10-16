<?php
class Usuario implements JsonSerializable
{
    private $_id;
    private $_nombre;
    private $_clave;
    private $_mail;
    private $_fechaCreacion;
    private $_fotoUsuario;

    public function __construct($id, $nombre, $clave, $mail, $fechaCreacion, $infoImagen = [])
    {
        $this->_id = $id;
        $this->_nombre = $nombre;
        $this->_clave = $clave;
        $this->_mail = $mail;
        $this->_fechaCreacion = $fechaCreacion;
        if (gettype($infoImagen) === 'array' && count($infoImagen) > 0) {
            $this->_fotoUsuario = $this->EstablecerFotoUsuario($infoImagen);
        } elseif (gettype($infoImagen) === 'string') {
            $this->_fotoUsuario = $infoImagen;
        }
    }

    public static function AltaUsuario($Usuario, $ext)
    {
        $nombreArchivo = './usuarios.' . $ext;
        switch ($ext) {
            case 'csv':
                $Usuario->AltaCSV($nombreArchivo);
                break;
            case 'json':
                $Usuario->AltaJSON($nombreArchivo);
                break;
            default:
                throw new Exception('Formato de archivo inálido. Solo permitido txt o json.');
        }
    }

    public static function MostrarUsuario($Usuario)
    {
        $fields = ['id', 'nombre', 'clave', 'mail', 'fechaCreacion'];
        echo '<ul>';
        for ($i = 0; $i < count($fields); $i++) {
            echo '<li>' . "$fields[$i]: " . $Usuario->{'_' . $fields[$i]} . '</li>';
        }
        echo '</ul>';
    }

    public static function LeerUsuarios($archivo)
    {
        $extensionImagen = explode(".", $archivo)[1];
        if (file_exists($archivo)) {
            $file = fopen($archivo, 'r');

            switch ($extensionImagen) {
                case 'json':
                    $strContenido = file_get_contents($archivo);
                    $jsonContenido = json_decode($strContenido, true);

                    foreach ($jsonContenido as $usuario) {
                        $usuario = new Usuario(
                            $usuario['_id'],
                            $usuario['_nombre'],
                            $usuario['_clave'],
                            $usuario['_mail'],
                            $usuario['_fechaCreacion'],
                            $usuario['_fotoUsuario'],
                        );
                        Usuario::MostrarUsuario($usuario);
                    }
                    break;
                case 'csv':
                    while (($data = fgetcsv($file)) !== FALSE) {
                        $usuario = new Usuario(rand(1, 10000), $data[0], $data[1], $data[2], date('Y-m-d_H:m:s'));
                        Usuario::MostrarUsuario($usuario);
                    }
                    break;
                default:
                    echo json_encode(['error' => 'Extension de archivo invalida.']);
                    break;
            }
            fclose($file);
        } else {
            echo json_encode(['error' => 'Archivo inexistente o inalcanzable.']);
        }
    }

    public static function BuscarUsuario($archivo, $clave, $mail)
    {
        $file = fopen($archivo, 'r');
        $resultado = 'Usuario no registrado';
        while (($data = fgetcsv($file)) !== FALSE) {
            if ($clave === $data[1] && $mail === $data[2]) {
                $resultado = 'Verificado';
                break;
            } else if ($clave != $data[1] && $mail === $data[2]) {
                $resultado = 'Error en los datos';
                break;
            }
        }
        fclose($file);
        return $resultado;
    }

    private function EstablecerFotoUsuario($arrImagen)
    {
        $extensiones = ['png', 'jpg', 'jpeg'];
        $extensionImagen = explode(".", $arrImagen['name'])[1];
        $tamañoImagen = $arrImagen['size'];
        $nombreImagen = (string) $this->_id . '_' . $arrImagen['name'];
        $pathImagen = 'img/' . $nombreImagen;

        if (!in_array($extensionImagen, $extensiones) && ($tamañoImagen < 100000)) {
            echo json_encode(['error' => 'Operacion fallida. Extensiones permitidas: png, jpg y jpeg. Tamaño permitido: 100 Kb maximo']);
        } else {
            if (!move_uploaded_file($arrImagen['tmp_name'], $pathImagen)) {
                echo json_encode(['error' => 'Ocurrio algun error al subir el fichero. No pudo guardarse.']);
            }
        }
        return $nombreImagen;
    }

    private function AbrirArchivo($nombreArchivo)
    {
        if (file_exists($nombreArchivo)) {
            $file = fopen($nombreArchivo, 'a');
        } else {
            $file = fopen($nombreArchivo, 'w');
        }
        return $file;
    }

    private function AltaCSV($ext)
    {
        $file = $this->AbrirArchivo($ext);
        $fieldList = [$this->GetFields()];
        foreach ($fieldList as $field) {
            fputcsv($file, $field);
        }
        fclose($file);
    }

    private function AltaJSON($nombreArchivo)
    {
        $jsonTotal = null;
        $strTotal = '';
        $strContenido = file_get_contents($nombreArchivo);

        $file = fopen($nombreArchivo, 'w');
        $jsonTotal = json_decode($strContenido, true);
        $jsonTotal[] = $this;
        $strTotal = json_encode($jsonTotal);

        fwrite($file, $strTotal);
        fclose($file);
    }

    private function GetFields()
    {
        $list = [];
        foreach ($this as $value) {
            array_push($list, $value);
        }
        return $list;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
?>