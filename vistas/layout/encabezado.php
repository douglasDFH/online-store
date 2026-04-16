<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($titulo ?? 'Tienda en Línea'); ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.php?pagina=inicio">
            <img src="https://getbootstrap.com/docs/4.5/assets/brand/bootstrap-solid.svg" width="30" height="30" alt="Logo" class="d-inline-block align-top">
            Tienda en Línea
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?pagina=inicio">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?pagina=carrito">Carrito</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?pagina=pago">Pagar</a>
                </li>
                
                <?php if (isset($_SESSION['es_admin']) && $_SESSION['es_admin']): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Administración
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="adminDropdown">
                            <a class="dropdown-item" href="index.php?pagina=admin_catalogos">Catálogos</a>
                            <a class="dropdown-item" href="index.php?pagina=admin_productos">Productos</a>
                            <a class="dropdown-item" href="index.php?pagina=admin_sucursales">Sucursales</a>
                            <a class="dropdown-item" href="index.php?pagina=admin_clientes">Clientes</a>
                            <a class="dropdown-item" href="index.php?pagina=admin_ventas">Ventas</a>
                        </div>
                    </li>
                <?php endif; ?>

                <?php if (isset($_SESSION['usuario'])): ?>
                    <li class="nav-item">
                        <span class="nav-link disabled">
                            <strong><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong>
                            <?php if (isset($_SESSION['es_admin']) && $_SESSION['es_admin']): ?>
                                <span class="badge badge-success">Admin</span>
                            <?php endif; ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?pagina=logout">Salir</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?pagina=login">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
