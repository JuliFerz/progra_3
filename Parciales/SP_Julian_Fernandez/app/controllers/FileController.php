<?php
class FileController
{
    public int $maxSize = 5120;
    private $path;
    public function __construct($path)
    {
        $this->path = $path;
    }
    
    public function setImage($objImg, $nameImg, $path='')
    {
        $maxSize = $this->maxSize;
        $extensions = ['png', 'jpg', 'jpeg'];
        $imgExt = explode(".", $objImg->getClientFilename())[1];
        $imgSize = $objImg->getSize() / 1024; // Kb
        $pathImagen = $this->path . $nameImg . '.png';

        if (!in_array($imgExt, $extensions) || ($imgSize > $maxSize)) {
            throw new Exception('Extensiones permitidas: png, jpg y jpeg. Tamanio permitido: '. ($maxSize / 1024) . 'Mb maximo');
        } else {
            if (!move_uploaded_file($objImg->getStream()->getMetadata('uri'), $pathImagen)) {
                throw new Exception('Ocurrio algun error al subir el fichero. No pudo guardarse.');
            }
        }
    }

    public function abrirArchivo($nombre, $ext)
    {
        $nombreArchivo = $this->path . $nombre . $ext;
        if (file_exists($nombreArchivo)) {
            $file = fopen($nombreArchivo, 'a');
        } else {
            $file = fopen($nombreArchivo, 'w');
        }
        return $file;
    }
}
?>