<?php require_once __DIR__ . '/layout/encabezado.php'; ?>
<div class="container mt-5">
    <h1 class="text-center">Bienvenido a nuestra tienda en línea</h1>
    <div class="row">
        <?php if (!empty($productos)): ?>
            <?php foreach ($productos as $producto): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <a href="recursos/imagenes/<?php echo htmlspecialchars($producto['imagen']); ?>" target="_blank">
                            <div class="card-img-top d-flex align-items-center justify-content-center bg-white" style="height: 200px; overflow: hidden;">
                                <img src="recursos/imagenes/<?php echo htmlspecialchars($producto['imagen']); ?>"
                                     class="img-fluid"
                                     alt="<?php echo htmlspecialchars($producto['nombre']); ?>"
                                     style="max-height: 200px; object-fit: contain;">
                            </div>
                        </a>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                            <p class="card-text mb-1"><small>Marca: <?php echo htmlspecialchars($producto['marca'] ?? 'N/D'); ?></small></p>
                            <p class="card-text mb-2"><small>Categoría: <?php echo htmlspecialchars($producto['categoria'] ?? 'N/D'); ?></small></p>
                            <p class="card-text"><strong>Precio: $<?php echo number_format($producto['precio'], 2); ?></strong></p>
                            <p class="card-text"><small>Stock disponible: <?php echo (int)($producto['stock'] ?? 0); ?></small></p>
                            <?php if (($producto['estado'] ?? '') === 'activo'): ?>
                                <a href="index.php?pagina=carrito&accion=agregar&id=<?php echo $producto['id_producto']; ?>"
                                   class="btn btn-primary">Agregar al carrito</a>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled>No disponible</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center w-100">No hay productos disponibles en este momento.</p>
        <?php endif; ?>
    </div>
</div>
<?php require_once __DIR__ . '/layout/pie.php'; ?>
