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
        $stock = (int)$stock;

        $stmtSp = $this->db->prepare("CALL sp_guardar_stock_sucursal(?, ?, ?)");
        if ($stmtSp) {
            $stmtSp->bind_param('iii', $codProducto, $codSucursal, $stock);
            $ok = $stmtSp->execute();
            $stmtSp->close();
            $this->limpiarResultadosPendientes();
            if ($ok) {
                return true;
            }
        }

        $sql = "INSERT INTO `DetalleProductoSucursal` (codProducto, codSucursal, stock)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE stock = VALUES(stock)";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('iii', $codProducto, $codSucursal, $stock);
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

    private function limpiarResultadosPendientes() {
        while ($this->db->more_results() && $this->db->next_result()) {
            $resultado = $this->db->use_result();
            if ($resultado instanceof mysqli_result) {
                $resultado->free();
            }
        }
    }
}
