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
                <li class="nav-item">
                    <a class="nav-link" href="index.php?pagina=admin_catalogos">Catalogos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?pagina=admin_productos">Productos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?pagina=admin_sucursales">Sucursales</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?pagina=admin_clientes">Clientes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?pagina=admin_ventas">Ventas</a>
                </li>
            </ul>
        </div>
    </nav>
