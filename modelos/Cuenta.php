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
}
