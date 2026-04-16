<?php
require_once __DIR__ . '/../config/database.php';

class Usuario {
    private $db;

    public function __construct() {
        $this->db = Database::conectar();
    }

    public function verificarCredenciales($usuario, $password) {
        $sql = "SELECT usuario FROM `Cuenta` WHERE usuario = ? AND password = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ss", $usuario, $password);
        $stmt->execute();
        $resultado = $stmt->get_result();

        return $resultado && $resultado->num_rows === 1;
    }
}
