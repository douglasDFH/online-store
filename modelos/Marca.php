<?php
require_once __DIR__ . '/../config/database.php';

class Marca {
    private $db;

    public function __construct() {
        $this->db = Database::conectar();
    }

    public function obtenerTodos() {
        $resultado = $this->db->query("SELECT cod, nombre FROM `Marca` ORDER BY cod DESC");
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function crear($nombre) {
        $stmt = $this->db->prepare("INSERT INTO `Marca` (nombre) VALUES (?)");
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('s', $nombre);
        return $stmt->execute();
    }

    public function actualizar($cod, $nombre) {
        $cod = (int)$cod;
        $stmt = $this->db->prepare("UPDATE `Marca` SET nombre = ? WHERE cod = ?");
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('si', $nombre, $cod);
        return $stmt->execute();
    }

    public function eliminar($cod) {
        $cod = (int)$cod;
        $stmt = $this->db->prepare("DELETE FROM `Marca` WHERE cod = ?");
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('i', $cod);
        return $stmt->execute();
    }
}
