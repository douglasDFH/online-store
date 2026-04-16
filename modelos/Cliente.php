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

    public function obtenerPorClave($ci, $usuarioCuenta) {
        $sql = "SELECT ci, nombres, apPaterno, apMaterno, correo, direccion, nroCelular, usuarioCuenta
                FROM `Cliente`
                WHERE ci = ? AND usuarioCuenta = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return null;
        }

        $stmt->bind_param('ss', $ci, $usuarioCuenta);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado ? $resultado->fetch_assoc() : null;
    }

    public function actualizar($ci, $usuarioCuenta, $nombres, $apPaterno, $apMaterno, $correo, $direccion, $nroCelular) {
        $sql = "UPDATE `Cliente`
                SET nombres = ?, apPaterno = ?, apMaterno = ?, correo = ?, direccion = ?, nroCelular = ?
                WHERE ci = ? AND usuarioCuenta = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('ssssssss', $nombres, $apPaterno, $apMaterno, $correo, $direccion, $nroCelular, $ci, $usuarioCuenta);
        return $stmt->execute();
    }

    public function eliminar($ci, $usuarioCuenta) {
        $sql = "DELETE FROM `Cliente` WHERE ci = ? AND usuarioCuenta = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('ss', $ci, $usuarioCuenta);
        return $stmt->execute();
    }
}
