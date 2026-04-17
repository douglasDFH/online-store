<?php
require_once __DIR__ . '/../config/database.php';

class Categoria {
    private $db;

    public function __construct() {
        $this->db = Database::conectar();
    }

    public function obtenerTodos() {
        $stmtSp = $this->db->prepare("CALL sp_listar_categorias()");
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

        $resultado = $this->db->query("SELECT cod, nombre FROM `Categoria` ORDER BY cod DESC");
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function crear($nombre) {
        $stmtSp = $this->db->prepare("CALL sp_crear_categoria(?)");
        if ($stmtSp) {
            $stmtSp->bind_param('s', $nombre);
            $ok = $stmtSp->execute();
            $stmtSp->close();
            $this->limpiarResultadosPendientes();
            if ($ok) {
                return true;
            }
        }

        $stmt = $this->db->prepare("INSERT INTO `Categoria` (nombre) VALUES (?)");
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('s', $nombre);
        return $stmt->execute();
    }

    public function actualizar($cod, $nombre) {
        $cod = (int)$cod;

        $stmtSp = $this->db->prepare("CALL sp_actualizar_categoria(?, ?)");
        if ($stmtSp) {
            $stmtSp->bind_param('is', $cod, $nombre);
            $ok = $stmtSp->execute();
            $stmtSp->close();
            $this->limpiarResultadosPendientes();
            if ($ok) {
                return true;
            }
        }

        $stmt = $this->db->prepare("UPDATE `Categoria` SET nombre = ? WHERE cod = ?");
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('si', $nombre, $cod);
        return $stmt->execute();
    }

    public function eliminar($cod) {
        $cod = (int)$cod;

        $stmtSp = $this->db->prepare("CALL sp_eliminar_categoria(?)");
        if ($stmtSp) {
            $stmtSp->bind_param('i', $cod);
            $ok = $stmtSp->execute();
            $stmtSp->close();
            $this->limpiarResultadosPendientes();
            if ($ok) {
                return true;
            }
        }

        $stmt = $this->db->prepare("DELETE FROM `Categoria` WHERE cod = ?");
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
