<?php
require_once __DIR__ . '/../config/database.php';

class Sucursal {
    private $db;

    public function __construct() {
        $this->db = Database::conectar();
    }

    public function obtenerTodas() {
        $resultado = $this->db->query("SELECT cod, nombre, direccion, nroTelefono FROM `Sucursal` ORDER BY cod DESC");
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtenerPorCod($cod) {
        $cod = (int)$cod;
        $stmt = $this->db->prepare("SELECT cod, nombre, direccion, nroTelefono FROM `Sucursal` WHERE cod = ?");
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param('i', $cod);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado ? $resultado->fetch_assoc() : null;
    }

    public function crear($nombre, $direccion, $nroTelefono) {
        $stmt = $this->db->prepare("INSERT INTO `Sucursal` (nombre, direccion, nroTelefono) VALUES (?, ?, ?)");
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('sss', $nombre, $direccion, $nroTelefono);
        return $stmt->execute();
    }

    public function actualizar($cod, $nombre, $direccion, $nroTelefono) {
        $cod = (int)$cod;
        $stmt = $this->db->prepare("UPDATE `Sucursal` SET nombre = ?, direccion = ?, nroTelefono = ? WHERE cod = ?");
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('sssi', $nombre, $direccion, $nroTelefono, $cod);
        return $stmt->execute();
    }

    public function eliminar($cod) {
        $cod = (int)$cod;
        $stmt = $this->db->prepare("DELETE FROM `Sucursal` WHERE cod = ?");
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('i', $cod);
        return $stmt->execute();
    }
}
