<?php
require_once __DIR__ . '/../config/database.php';

class Industria {
    private $db;

    public function __construct() {
        $this->db = Database::conectar();
    }

    public function obtenerTodos() {
        $stmtSp = $this->db->prepare("CALL sp_listar_industrias()");
        if ($stmtSp) {
            $ok = $stmtSp->execute();
            if ($ok) {
                $resultado = $stmtSp->get_result();
                $datos = $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
                $stmtSp->close();
                $this->limpiarResultadosPendientes();
                return $datos;
            }
            $stmtSp->close();
            $this->limpiarResultadosPendientes();
        }

        $resultado = $this->db->query("SELECT cod, nombre FROM `Industria` ORDER BY cod DESC");
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function crear($nombre) {
        $stmtSp = $this->db->prepare("CALL sp_crear_industria(?)");
        if ($stmtSp) {
            $stmtSp->bind_param('s', $nombre);
            $ok = $stmtSp->execute();
            $stmtSp->close();
            $this->limpiarResultadosPendientes();
            if ($ok) {
                return true;
            }
        }

        $stmt = $this->db->prepare("INSERT INTO `Industria` (nombre) VALUES (?)");
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('s', $nombre);
        return $stmt->execute();
    }

    public function actualizar($cod, $nombre) {
        $cod = (int)$cod;

        $stmtSp = $this->db->prepare("CALL sp_actualizar_industria(?, ?)");
        if ($stmtSp) {
            $stmtSp->bind_param('is', $cod, $nombre);
            $ok = $stmtSp->execute();
            $stmtSp->close();
            $this->limpiarResultadosPendientes();
            if ($ok) {
                return true;
            }
        }

        $stmt = $this->db->prepare("UPDATE `Industria` SET nombre = ? WHERE cod = ?");
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('si', $nombre, $cod);
        return $stmt->execute();
    }

    public function eliminar($cod) {
        $cod = (int)$cod;

        $stmtSp = $this->db->prepare("CALL sp_eliminar_industria(?)");
        if ($stmtSp) {
            $stmtSp->bind_param('i', $cod);
            $ok = $stmtSp->execute();
            $stmtSp->close();
            $this->limpiarResultadosPendientes();
            if ($ok) {
                return true;
            }
        }

        $stmt = $this->db->prepare("DELETE FROM `Industria` WHERE cod = ?");
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('i', $cod);
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
