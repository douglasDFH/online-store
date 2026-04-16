-- Seed para mydb
-- Ejecutar despues de importar mydb.sql

USE `mydb`;

SET FOREIGN_KEY_CHECKS = 0;

-- Limpiar datos previos para seed reproducible
DELETE FROM `DetalleNotaVenta`;
DELETE FROM `NotaVenta`;
DELETE FROM `DetalleProductoSucursal`;
DELETE FROM `Producto`;
DELETE FROM `Sucursal`;
DELETE FROM `Cliente`;
DELETE FROM `Cuenta`;
DELETE FROM `Marca`;
DELETE FROM `Industria`;
DELETE FROM `Categoria`;

SET FOREIGN_KEY_CHECKS = 1;

START TRANSACTION;

-- Catalogos base
INSERT INTO `Marca` (`cod`, `nombre`) VALUES
(1, 'Urban Wear'),
(2, 'Andes Sport'),
(3, 'Neo Classic');

INSERT INTO `Industria` (`cod`, `nombre`) VALUES
(1, 'Textil'),
(2, 'Calzado'),
(3, 'Accesorios');

INSERT INTO `Categoria` (`cod`, `nombre`) VALUES
(1, 'Sudaderas'),
(2, 'Zapatillas'),
(3, 'Gorras');

INSERT INTO `Sucursal` (`cod`, `nombre`, `direccion`, `nroTelefono`) VALUES
(1, 'Sucursal Plancity', 'Av. Principal Mechero', 74940820),
(2, 'Sucursal Norte', 'Calle siempre viva', 68779130);

-- Cuentas y clientes base (incluye el cliente_demo usado por el checkout)
INSERT INTO `Cuenta` (`usuario`, `password`) VALUES
('cliente_demo', '$2y$10$ROGQKafhG0ZaDGJWPBe5rOqLNrvQ/W3Y86DScwo1l1eLP3v4Hc/0S'),
('admin', '$2y$10$VDqBzxfDKBXmB8p/OHx0G.NBf0PEBmD9AQFfeY.KYsQa7myy/y38e');

INSERT INTO `Cliente` (`ci`, `nombres`, `apPaterno`, `apMaterno`, `correo`, `direccion`, `nroCelular`, `usuarioCuenta`) VALUES
('0000000000', 'Consumidor', 'Final', 'Demo', 'demo@tienda.local', 'Sin direccion', '00000000', 'cliente_demo'),
('78945612', 'Carlos', 'Rojas', 'Mamani', 'carlos@correo.com', 'Zona Sur 100', '70000001', 'admin');

-- Productos de ejemplo
INSERT INTO `Producto` (`cod`, `nombre`, `descripcion`, `precio`, `imagen`, `estado`, `codMarca`, `codIndustria`, `codCategoria`) VALUES
(1, 'Sudadera Basica', 'Sudadera de algodon unisex', 250.00, 'sudadera.png', 'activo', 1, 1, 1),
(2, 'Sudadera Premium', 'Sudadera gruesa para invierno', 320.00, 'sudadera.png', 'activo', 3, 1, 1),
(3, 'Zapatilla Running', 'Calzado deportivo liviano', 410.00, 'sudadera.png', 'activo', 2, 2, 2),
(4, 'Gorra Street', 'Gorra ajustable de uso diario', 95.00, 'sudadera.png', 'inactivo', 1, 3, 3);

-- Stock por sucursal
INSERT INTO `DetalleProductoSucursal` (`codProducto`, `codSucursal`, `stock`) VALUES
(1, 1, '12'),
(1, 2, '6'),
(2, 1, '7'),
(3, 1, '9'),
(3, 2, '4'),
(4, 1, '0');

COMMIT;

-- Reiniciar autoincrement para futuras inserciones manuales
ALTER TABLE `Marca` AUTO_INCREMENT = 4;
ALTER TABLE `Industria` AUTO_INCREMENT = 4;
ALTER TABLE `Categoria` AUTO_INCREMENT = 4;
ALTER TABLE `Sucursal` AUTO_INCREMENT = 3;
ALTER TABLE `Producto` AUTO_INCREMENT = 5;
