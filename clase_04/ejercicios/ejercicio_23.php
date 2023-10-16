<?php
/*
    Ejercicio: 23
    Enunciado: 
        Archivo: registro.php
        Método: POST

        Recibe los datos del usuario(nombre, clave, mail) por POST, crea un ID autoincremental(emulado, puede ser un random de 1 a 10.000). 
        Crear un dato con la fecha de registro , toma todos los datos y utilizar sus métodos para poder hacer el alta, guardando los datos en usuarios.json y subir la imagen al servidor en la carpeta:
            Usuario/Fotos/.

        Retorna si se pudo agregar o no.
        Cada usuario se agrega en un renglón diferente al anterior.
        Hacer los métodos necesarios en la clase usuario.

    Alumno: Julian Fernandez
*/

/*
cURL:
curl --location 'http://localhost/progra_3/clase_04/ejercicios/index.php?accion=registro' \
--form 'nombre="Test"' \
--form 'clave="123abc"' \
--form 'mail="test@mail.com"' \
--form 'imagen=@"piano.jpg"'

RESPONSE:
{"success":"Operacion exitosa. Usuario generado y guardado en .\/usuarios.json"}
*/

?>