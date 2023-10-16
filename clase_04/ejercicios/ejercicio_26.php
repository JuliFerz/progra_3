<?php
/*
    Ejercicio: 26
    Enunciado: 
        Archivo: RealizarVenta.php
        Método: POST

        Recibe los datos del producto (código de barra), del usuario (el id) y la cantidad de ítems, por POST.
        Verificar que el usuario y el producto exista y tenga stock.
        Crea un ID autoincremental(emulado, puede ser un random de 1 a 10.000).
        Carga los datos necesarios para guardar la venta en un nuevo renglón.

        Retorna un:
            "Venta realizada" -> Se hizo una venta
            "No se pudo hacer" -> si no se pudo hacer
        Hacer los métodos necesarias en las clases

    Alumno: Julian Fernandez
*/

/*
cURL:
curl --location 'http://localhost/progra_3/clase_04/ejercicios/index.php?accion=realizarVenta' \
--form 'ean="12374"' \
--form 'idUsuario="6047"' \
--form 'cantidad="1"'

RESPONSE:
{"success":"Venta realizada"}
*/

?>