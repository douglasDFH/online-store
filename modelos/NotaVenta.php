<?php
require_once __DIR__ . '/../config/database.php';

class NotaVenta {
    private $db;

    public function __construct() {
        $this->db = Database::conectar();
    }

    public function obtenerTodasConResumen() {
        $sql = "SELECT nv.nro, nv.fechaHora, nv.ciCliente,
                       CONCAT(cl.nombres, ' ', cl.apPaterno, ' ', cl.apMaterno) AS cliente,
                       COALESCE(SUM(dnv.cant), 0) AS totalItems
                FROM `NotaVenta` nv
                INNER JOIN `Cliente` cl ON cl.ci = nv.ciCliente
                LEFT JOIN `DetalleNotaVenta` dnv ON dnv.nroNotaVenta = nv.nro
                GROUP BY nv.nro, nv.fechaHora, nv.ciCliente, cl.nombres, cl.apPaterno, cl.apMaterno
                ORDER BY nv.nro DESC";
        $resultado = $this->db->query($sql);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtenerDetallesPorNota($nroNota) {
        $nroNota = (int)$nroNota;
        $sql = "SELECT dnv.nroNotaVenta, dnv.item, dnv.cant, dnv.codProducto, p.nombre AS producto, p.precio
                FROM `DetalleNotaVenta` dnv
                INNER JOIN `Producto` p ON p.cod = dnv.codProducto
                WHERE dnv.nroNotaVenta = ?
                ORDER BY dnv.item ASC";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return [];
        }

        $stmt->bind_param('i', $nroNota);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }
}
