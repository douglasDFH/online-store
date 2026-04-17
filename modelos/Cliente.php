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

    public function crearConCuenta($usuario, $passwordHash, $ci, $nombres, $apPaterno, $apMaterno, $correo, $direccion, $nroCelular) {
        $stmtSp = $this->db->prepare("CALL sp_crear_cliente_con_cuenta(?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmtSp) {
            $stmtSp->bind_param('sssssssss', $usuario, $passwordHash, $ci, $nombres, $apPaterno, $apMaterno, $correo, $direccion, $nroCelular);
            $ok = $stmtSp->execute();
            $stmtSp->close();
            $this->limpiarResultadosPendientes();
            if ($ok) {
                return true;
            }
        }

        $this->db->begin_transaction();
        try {
            $stmtCuenta = $this->db->prepare("INSERT INTO `Cuenta` (usuario, password) VALUES (?, ?)");
            if (!$stmtCuenta) {
                throw new Exception('No se pudo crear la cuenta.');
            }
            $stmtCuenta->bind_param('ss', $usuario, $passwordHash);
            if (!$stmtCuenta->execute()) {
                throw new Exception('No se pudo crear la cuenta.');
            }

            $okCliente = $this->crear($ci, $nombres, $apPaterno, $apMaterno, $correo, $direccion, $nroCelular, $usuario);
            if (!$okCliente) {
                throw new Exception('No se pudo crear el cliente.');
            }

            $this->db->commit();
            return true;
        } catch (Throwable $e) {
            $this->db->rollback();
            return false;
        }
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

    public function actualizarConPassword($ci, $usuarioCuenta, $nombres, $apPaterno, $apMaterno, $correo, $direccion, $nroCelular, $passwordHash) {
        $passwordHash = (string)$passwordHash;

        $stmtSp = $this->db->prepare("CALL sp_actualizar_cliente_y_password(?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmtSp) {
            $stmtSp->bind_param('sssssssss', $ci, $usuarioCuenta, $nombres, $apPaterno, $apMaterno, $correo, $direccion, $nroCelular, $passwordHash);
            $ok = $stmtSp->execute();
            $stmtSp->close();
            $this->limpiarResultadosPendientes();
            if ($ok) {
                return true;
            }
        }

        $okCliente = $this->actualizar($ci, $usuarioCuenta, $nombres, $apPaterno, $apMaterno, $correo, $direccion, $nroCelular);
        if (!$okCliente) {
            return false;
        }

        if ($passwordHash !== '') {
            $stmtCuenta = $this->db->prepare("UPDATE `Cuenta` SET password = ? WHERE usuario = ?");
            if (!$stmtCuenta) {
                return false;
            }
            $stmtCuenta->bind_param('ss', $passwordHash, $usuarioCuenta);
            return $stmtCuenta->execute();
        }

        return true;
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

    public function eliminarClienteYCuentaSegura($ci, $usuarioCuenta) {
        $stmtSp = $this->db->prepare("CALL sp_eliminar_cliente_y_cuenta_segura(?, ?)");
        if ($stmtSp) {
            $stmtSp->bind_param('ss', $ci, $usuarioCuenta);
            $ok = $stmtSp->execute();
            $stmtSp->close();
            $this->limpiarResultadosPendientes();
            if ($ok) {
                return true;
            }
        }

        return $this->eliminar($ci, $usuarioCuenta);
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
