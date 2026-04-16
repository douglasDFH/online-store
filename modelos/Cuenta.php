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

    public function obtenerPorUsuario($usuario) {
        $stmt = $this->db->prepare("SELECT usuario, password FROM `Cuenta` WHERE usuario = ? LIMIT 1");
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param('s', $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado && $resultado->num_rows > 0 ? $resultado->fetch_assoc() : null;
    }

    public function verificarCredenciales($usuario, $password) {
        $cuenta = $this->obtenerPorUsuario($usuario);
        if (!$cuenta) {
            return null;
        }

        $passwordGuardado = (string)$cuenta['password'];
        $passwordValido = password_verify($password, $passwordGuardado) || hash_equals($passwordGuardado, $password);

        if (!$passwordValido) {
            return null;
        }

        // Migra passwords legacy en texto plano a hash seguro al primer login exitoso.
        if (!$this->esHashPassword($passwordGuardado)) {
            $this->actualizarPassword($usuario, $password);
        }

        return $cuenta;
    }

    public function crear($usuario, $password) {
        $passwordNormalizado = $this->normalizarPassword($password);

        $stmt = $this->db->prepare("INSERT INTO `Cuenta` (usuario, password) VALUES (?, ?)");
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('ss', $usuario, $passwordNormalizado);
        return $stmt->execute();
    }

    public function actualizarPassword($usuario, $password) {
        $passwordNormalizado = $this->normalizarPassword($password);

        $stmt = $this->db->prepare("UPDATE `Cuenta` SET password = ? WHERE usuario = ?");
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('ss', $passwordNormalizado, $usuario);
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

    private function esHashPassword($password) {
        $info = password_get_info((string)$password);
        return isset($info['algo']) && $info['algo'] !== 0;
    }

    private function normalizarPassword($password) {
        $password = (string)$password;
        if ($this->esHashPassword($password)) {
            return $password;
        }
        return password_hash($password, PASSWORD_DEFAULT);
    }
}
