/*
    TP: N°1 (MySQL)
    Alumno: Julian Fernandez
*/

-- 1. Obtener los detalles completos de todos los usuarios, ordenados alfabéticamente.
SELECT * FROM usuario ORDER BY apellido;

-- 2. Obtener los detalles completos de todos los productos líquidos.
SELECT * FROM producto WHERE tipo = 'liquido';

-- 3. Obtener todas las compras en los cuales la cantidad esté entre 6 y 10 inclusive.
SELECT * FROM venta WHERE cantidad BETWEEN 6 and 10;

-- 4. Obtener la cantidad total de todos los productos vendidos.
SELECT SUM(cantidad) as 'Cantidad total vendida' FROM venta;

-- 5. Mostrar los primeros 3 números de productos que se han enviado.
SELECT codigo_de_barra FROM producto LIMIT 3;

-- 6. Mostrar los nombres del usuario y los nombres de los productos de cada venta.
SELECT v.id_producto, v.id_usuario, v.cantidad, v.fecha_de_venta, u.nombre as 'Nombre Usuario', p.nombre as 'Nombre Producto'
	FROM venta v
    INNER JOIN usuario u ON v.id_usuario = u.id
    INNER JOIN producto p ON v.id_producto = p.id;

-- 7. Indicar el monto (cantidad * precio) por cada una de las ventas.
SELECT v.*, (v.cantidad * p.precio) as 'Precio Producto'
FROM venta v
	INNER JOIN producto p ON v.id_producto = p.id;

-- 8. Obtener la cantidad total del producto 1003 vendido por el usuario 104.
SELECT SUM(cantidad) as 'Cantidad Vendida' FROM venta WHERE id_producto = 1003 and id_usuario = 104;

-- 9. Obtener todos los números de los productos vendidos por algún usuario de ‘Avellaneda’.
SELECT v.id_producto 
	FROM venta v
	INNER JOIN usuario u ON v.id_usuario = u.id
	WHERE u.localidad like 'avellaneda';

-- 10. Obtener los datos completos de los usuarios cuyos nombres contengan la letra ‘u’.
SELECT * FROM usuario WHERE nombre like '%u%' or apellido like '%u%';

-- 11. Traer las ventas entre junio del 2020 y febrero 2021.
SELECT * FROM venta WHERE fecha_de_venta between '2020-01-01' and '2021-02-28';

-- 12. Obtener los usuarios registrados antes del 2021.
SELECT * FROM usuario WHERE fecha_de_registro < '2021-01-01';

-- 13. Agregar el producto llamado ‘Chocolate’, de tipo Sólido y con un precio de 25,35.
INSERT INTO producto (`codigo_de_barra`, `nombre`, `tipo`, `stock`, `precio`, `fecha_de_creacion`, `fecha_de_modificacion`) VALUES ('', 'Chocolate', 'solido', 0, 25.35, NOW(), NOW());
SELECT * FROM producto;

-- 14. Insertar un nuevo usuario .
INSERT INTO `usuario` (`nombre`, `apellido`, `clave`, `mail`, `fecha_de_registro`, `localidad`) VALUES ('Julian', 'Fernandez', 0231, 'jfernandez@unmail.com', '2023/08/26', 'Wilde');

-- 15. Cambiar los precios de los productos de tipo sólido a 66,60.
-- SELECT * FROM producto WHERE tipo = 'solido';
UPDATE producto SET precio = 66.6 WHERE tipo = 'solido';

-- 16. Cambiar el stock a 0 de todos los productos cuyas cantidades de stock sean menores a 20 inclusive.
-- SELECT * FROM producto WHERE stock <= 20;
UPDATE producto set stock = 0 WHERE stock <= 20;

-- 17. Eliminar el producto número 1010.
-- SELECT * FROM producto WHERE id = 1010;
DELETE FROM producto WHERE id = 1010;

-- 18. Eliminar a todos los usuarios que no han vendido productos.
DELETE FROM usuario WHERE id IN (SELECT u.id FROM usuario u LEFT JOIN venta v ON u.id = v.id_usuario WHERE v.id_usuario IS NULL);