<?php
require_once __DIR__ . '/../config/database.php';

class Cuenta {
    private $db;

    public function __construct() {
        $this->db = Database::conectar();
    }

    public function obtenerTodas() {
        $resultado = $this->db->query("SELECT usuario, password FROM `Cuenta` ORDER BY usuario ASC");
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function crear($usuario, $password) {
        $stmt = $this->db->prepare("INSERT INTO `Cuenta` (usuario, password) VALUES (?, ?)");
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('ss', $usuario, $password);
        return $stmt->execute();
    }

    public function actualizarPassword($usuario, $password) {
        $stmt = $this->db->prepare("UPDATE `Cuenta` SET password = ? WHERE usuario = ?");
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('ss', $password, $usuario);
        return $stmt->execute();
    }

    public function tieneClienteAsociado($usuario) {
        $stmt = $this->db->prepare("SELECT 1 FROM `Cliente` WHERE usuarioCuenta = ? LIMIT 1");
        if (!$stmt) {
            return true;
        }
        $stmt->bind_param('s', $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado && $resultado->num_rows > 0;
    }

    public function eliminar($usuario) {
        $stmt = $this->db->prepare("DELETE FROM `Cuenta` WHERE usuario = ?");
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('s', $usuario);
        return $stmt->execute();
    }
}
