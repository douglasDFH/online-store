<?php
session_start();

$pagina = $_GET['pagina'] ?? 'inicio';

$paginasPermitidas = [
    'inicio',
    'carrito',
    'pago',
    'pago_exitoso',
    'login',
    'logout',
    'admin_catalogos',
    'admin_sucursales',
    'admin_clientes',
    'admin_productos',
    'admin_ventas'
];

if (!in_array($pagina, $paginasPermitidas)) {
    $pagina = 'inicio';
}

// Validar acceso a páginas administrativas
$paginasAdmin = ['admin_catalogos', 'admin_sucursales', 'admin_clientes', 'admin_productos', 'admin_ventas'];
if (in_array($pagina, $paginasAdmin)) {
    if (!isset($_SESSION['usuario']) || !isset($_SESSION['es_admin']) || !$_SESSION['es_admin']) {
        header('Location: index.php?pagina=login');
        exit();
    }
}

switch ($pagina) {
    case 'inicio':
        require_once __DIR__ . '/controladores/InicioControlador.php';
        (new InicioControlador())->index();
        break;
    case 'carrito':
        require_once __DIR__ . '/controladores/CarritoControlador.php';
        (new CarritoControlador())->index();
        break;
    case 'pago':
        require_once __DIR__ . '/controladores/PagoControlador.php';
        (new PagoControlador())->index();
        break;
    case 'pago_exitoso':
        require_once __DIR__ . '/controladores/PagoControlador.php';
        (new PagoControlador())->exitoso();
        break;
    case 'login':
        require_once __DIR__ . '/controladores/AutenticacionControlador.php';
        (new AutenticacionControlador())->login();
        break;
    case 'logout':
        require_once __DIR__ . '/controladores/AutenticacionControlador.php';
        (new AutenticacionControlador())->logout();
        break;
    case 'admin_catalogos':
        require_once __DIR__ . '/controladores/AdminControlador.php';
        (new AdminControlador())->catalogos();
        break;
    case 'admin_sucursales':
        require_once __DIR__ . '/controladores/AdminControlador.php';
        (new AdminControlador())->sucursales();
        break;
    case 'admin_clientes':
        require_once __DIR__ . '/controladores/AdminControlador.php';
        (new AdminControlador())->clientes();
        break;
    case 'admin_productos':
        require_once __DIR__ . '/controladores/AdminControlador.php';
        (new AdminControlador())->productos();
        break;
    case 'admin_ventas':
        require_once __DIR__ . '/controladores/AdminControlador.php';
        (new AdminControlador())->ventas();
        break;
}
