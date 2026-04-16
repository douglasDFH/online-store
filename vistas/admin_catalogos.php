<?php require_once __DIR__ . '/layout/encabezado.php'; ?>
<div class="container mt-4">
    <h1 class="mb-4">Administracion de Catalogos</h1>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4">
            <h4>Marca</h4>
            <form method="POST" action="index.php?pagina=admin_catalogos" class="mb-3">
                <input type="hidden" name="tipo" value="marca">
                <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre de marca" required>
                <button class="btn btn-primary btn-sm" type="submit">Agregar</button>
            </form>
            <ul class="list-group">
                <?php foreach ($marcas as $fila): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo htmlspecialchars($fila['nombre']); ?>
                        <a href="index.php?pagina=admin_catalogos&eliminar_tipo=marca&cod=<?php echo (int)$fila['cod']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="col-md-4">
            <h4>Categoria</h4>
            <form method="POST" action="index.php?pagina=admin_catalogos" class="mb-3">
                <input type="hidden" name="tipo" value="categoria">
                <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre de categoria" required>
                <button class="btn btn-primary btn-sm" type="submit">Agregar</button>
            </form>
            <ul class="list-group">
                <?php foreach ($categorias as $fila): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo htmlspecialchars($fila['nombre']); ?>
                        <a href="index.php?pagina=admin_catalogos&eliminar_tipo=categoria&cod=<?php echo (int)$fila['cod']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="col-md-4">
            <h4>Industria</h4>
            <form method="POST" action="index.php?pagina=admin_catalogos" class="mb-3">
                <input type="hidden" name="tipo" value="industria">
                <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre de industria" required>
                <button class="btn btn-primary btn-sm" type="submit">Agregar</button>
            </form>
            <ul class="list-group">
                <?php foreach ($industrias as $fila): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo htmlspecialchars($fila['nombre']); ?>
                        <a href="index.php?pagina=admin_catalogos&eliminar_tipo=industria&cod=<?php echo (int)$fila['cod']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/layout/pie.php'; ?>
