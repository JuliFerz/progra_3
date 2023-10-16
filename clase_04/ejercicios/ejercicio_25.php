<?php
/*
    Ejercicio: 25
    Enunciado: 
        Archivo: altaProducto.php
        Método: POST
    
        Recibe los datos del producto (código de barra (6 sifras), nombre, tipo, stock, precio) por POST.
        Crea un ID autoincremental (emulado, puede ser un random de 1 a 10.000).
        Crear un objeto y utilizar sus métodos para poder verificar si es un producto existente, si ya existe el producto se le suma el stock, de lo contrario se agrega al documento en un nuevo renglón.

        Retorna un :
            "Ingresado" si es un producto nuevo
            "Actualizado" si ya existía y se actualiza el stock.
            "no se pudo hacer" si no se pudo hacer
        Hacer los métodos necesarios en la clase

    Alumno: Julian Fernandez
*/

/*
cURL:
curl --location 'http://localhost/progra_3/clase_04/ejercicios/index.php?accion=altaProducto' \
--form 'ean="12374"' \
--form 'nombre="Celular"' \
--form 'tipo="Smartphone"' \
--form 'stock="6"' \
--form 'precio="254897"'

RESPONSE:
{"success":"Ingresado"}

*/

?>