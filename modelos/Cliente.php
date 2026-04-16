<?php
require_once __DIR__ . '/../config/database.php';

class Cliente {
    private $db;

    public function __construct() {
        $this->db = Database::conectar();
    }

    public function obtenerTodos() {
        $sql = "SELECT c.ci, c.nombres, c.apPaterno, c.apMaterno, c.correo, c.direccion, c.nroCelular, c.usuarioCuenta
                FROM `Cliente` c
                ORDER BY c.ci DESC";
        $resultado = $this->db->query($sql);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function crear($ci, $nombres, $apPaterno, $apMaterno, $correo, $direccion, $nroCelular, $usuarioCuenta) {
        $sql = "INSERT INTO `Cliente` (ci, nombres, apPaterno, apMaterno, correo, direccion, nroCelular, usuarioCuenta)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('ssssssss', $ci, $nombres, $apPaterno, $apMaterno, $correo, $direccion, $nroCelular, $usuarioCuenta);
        return $stmt->execute();
    }
}
