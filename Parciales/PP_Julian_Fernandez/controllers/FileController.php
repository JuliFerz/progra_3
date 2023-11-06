<?php

class FileController
{
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function read()
    {
        if (file_exists($this->path)) {
            $jsonStr = file_get_contents($this->path);
            return json_decode($jsonStr, true);
        }
        return [];
    }

    public function readAsObject()
    {
        if (file_exists($this->path)) {
            $jsonStr = file_get_contents($this->path);
            return json_decode($jsonStr);
        }
        return [];
    }

    public function write($data)
    {
        $jsonStr = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($this->path, $jsonStr);
    }

    public function setImage($objImg, $nameImg, $path)
    {
        $extensions = ['png', 'jpg', 'jpeg'];
        $imgExt = explode(".", $objImg['name'])[1];
        $imgSize = $objImg['size'] / 1024; // Kb
        $pathImagen = $path . '/2023/' . $nameImg . '.png';

        if (!in_array($imgExt, $extensions) || ($imgSize > 2048)) {
            throw new Exception('Extensiones permitidas: png, jpg y jpeg. Tamanio permitido: 2 Mb maximo');
        } else {
            if (!move_uploaded_file($objImg['tmp_name'], $pathImagen)) {
                throw new Exception('Ocurrio algun error al subir el fichero. No pudo guardarse.');
            }
        }
    }

    public function moveImage($nameImg, $pathOrigen){
        $pathDestino = $this->path . $nameImg . '.png';
        if (!rename($pathOrigen, $pathDestino)) {
            throw new Exception('Ocurrio algun error al mover la imagen');
        }
    }

    public function getMinId()
    {
        return 100000;
    }
    public function getMaxId()
    {
        return 999999;
    }
}
?>