<?php
require_once __DIR__ . '/../config/database.php';

class DetalleProductoSucursal {
    private $db;

    public function __construct() {
        $this->db = Database::conectar();
    }

    public function obtenerTodos() {
        $sql = "SELECT dps.codProducto, p.nombre AS producto, dps.codSucursal, s.nombre AS sucursal, dps.stock
                FROM `DetalleProductoSucursal` dps
                INNER JOIN `Producto` p ON p.cod = dps.codProducto
                INNER JOIN `Sucursal` s ON s.cod = dps.codSucursal
                ORDER BY dps.codProducto DESC, dps.codSucursal ASC";

        $resultado = $this->db->query($sql);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function guardarStock($codProducto, $codSucursal, $stock) {
        $codProducto = (int)$codProducto;
        $codSucursal = (int)$codSucursal;
        $stock = (string)$stock;

        $sql = "INSERT INTO `DetalleProductoSucursal` (codProducto, codSucursal, stock)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE stock = VALUES(stock)";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('iis', $codProducto, $codSucursal, $stock);
        return $stmt->execute();
    }

    public function eliminar($codProducto, $codSucursal) {
        $codProducto = (int)$codProducto;
        $codSucursal = (int)$codSucursal;
        $stmt = $this->db->prepare("DELETE FROM `DetalleProductoSucursal` WHERE codProducto = ? AND codSucursal = ?");
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('ii', $codProducto, $codSucursal);
        return $stmt->execute();
    }
}
