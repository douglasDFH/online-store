<?php
require_once __DIR__ . '/../modelos/Cuenta.php';

class AutenticacionControlador {
    public function login() {
        $mensaje = null;
        $tipoMensaje = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = trim($_POST['usuario'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if ($usuario !== '' && $password !== '') {
                $cuentaModel = new Cuenta();
                $cuenta = $cuentaModel->verificarCredenciales($usuario, $password);

                if ($cuenta) {
                    // Login exitoso - guardar en sesión
                    $_SESSION['usuario'] = $cuenta['usuario'];
                    $_SESSION['es_admin'] = ($cuenta['usuario'] === 'admin');

                    header('Location: index.php?pagina=inicio');
                    exit();
                } else {
                    $mensaje = 'Usuario o contraseña incorrectos.';
                    $tipoMensaje = 'danger';
                }
            } else {
                $mensaje = 'Por favor ingresa usuario y contraseña.';
                $tipoMensaje = 'warning';
            }
        }

        $titulo = 'Login - Tienda en Línea';
        require_once __DIR__ . '/../vistas/layout/encabezado.php';
        require_once __DIR__ . '/../vistas/login.php';
        require_once __DIR__ . '/../vistas/layout/pie.php';
    }

    public function logout() {
        session_destroy();
        header('Location: index.php?pagina=inicio');
        exit();
    }
}
