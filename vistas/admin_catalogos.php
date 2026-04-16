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
                <input type="hidden" name="accion" value="<?php echo ($edicion['tipo'] === 'marca') ? 'editar' : 'crear'; ?>">
                <input type="hidden" name="tipo" value="marca">
                <input type="hidden" name="cod" value="<?php echo ($edicion['tipo'] === 'marca') ? (int)$edicion['cod'] : 0; ?>">
                <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre de marca" value="<?php echo ($edicion['tipo'] === 'marca') ? htmlspecialchars($edicion['nombre']) : ''; ?>" required>
                <button class="btn btn-primary btn-sm" type="submit"><?php echo ($edicion['tipo'] === 'marca') ? 'Actualizar' : 'Agregar'; ?></button>
                <?php if ($edicion['tipo'] === 'marca'): ?>
                    <a href="index.php?pagina=admin_catalogos" class="btn btn-secondary btn-sm">Cancelar</a>
                <?php endif; ?>
            </form>
            <ul class="list-group">
                <?php foreach ($marcas as $fila): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo htmlspecialchars($fila['nombre']); ?>
                        <span>
                            <a href="index.php?pagina=admin_catalogos&editar_tipo=marca&cod=<?php echo (int)$fila['cod']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="index.php?pagina=admin_catalogos&eliminar_tipo=marca&cod=<?php echo (int)$fila['cod']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="col-md-4">
            <h4>Categoria</h4>
            <form method="POST" action="index.php?pagina=admin_catalogos" class="mb-3">
                <input type="hidden" name="accion" value="<?php echo ($edicion['tipo'] === 'categoria') ? 'editar' : 'crear'; ?>">
                <input type="hidden" name="tipo" value="categoria">
                <input type="hidden" name="cod" value="<?php echo ($edicion['tipo'] === 'categoria') ? (int)$edicion['cod'] : 0; ?>">
                <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre de categoria" value="<?php echo ($edicion['tipo'] === 'categoria') ? htmlspecialchars($edicion['nombre']) : ''; ?>" required>
                <button class="btn btn-primary btn-sm" type="submit"><?php echo ($edicion['tipo'] === 'categoria') ? 'Actualizar' : 'Agregar'; ?></button>
                <?php if ($edicion['tipo'] === 'categoria'): ?>
                    <a href="index.php?pagina=admin_catalogos" class="btn btn-secondary btn-sm">Cancelar</a>
                <?php endif; ?>
            </form>
            <ul class="list-group">
                <?php foreach ($categorias as $fila): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo htmlspecialchars($fila['nombre']); ?>
                        <span>
                            <a href="index.php?pagina=admin_catalogos&editar_tipo=categoria&cod=<?php echo (int)$fila['cod']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="index.php?pagina=admin_catalogos&eliminar_tipo=categoria&cod=<?php echo (int)$fila['cod']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="col-md-4">
            <h4>Industria</h4>
            <form method="POST" action="index.php?pagina=admin_catalogos" class="mb-3">
                <input type="hidden" name="accion" value="<?php echo ($edicion['tipo'] === 'industria') ? 'editar' : 'crear'; ?>">
                <input type="hidden" name="tipo" value="industria">
                <input type="hidden" name="cod" value="<?php echo ($edicion['tipo'] === 'industria') ? (int)$edicion['cod'] : 0; ?>">
                <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre de industria" value="<?php echo ($edicion['tipo'] === 'industria') ? htmlspecialchars($edicion['nombre']) : ''; ?>" required>
                <button class="btn btn-primary btn-sm" type="submit"><?php echo ($edicion['tipo'] === 'industria') ? 'Actualizar' : 'Agregar'; ?></button>
                <?php if ($edicion['tipo'] === 'industria'): ?>
                    <a href="index.php?pagina=admin_catalogos" class="btn btn-secondary btn-sm">Cancelar</a>
                <?php endif; ?>
            </form>
            <ul class="list-group">
                <?php foreach ($industrias as $fila): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo htmlspecialchars($fila['nombre']); ?>
                        <span>
                            <a href="index.php?pagina=admin_catalogos&editar_tipo=industria&cod=<?php echo (int)$fila['cod']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="index.php?pagina=admin_catalogos&eliminar_tipo=industria&cod=<?php echo (int)$fila['cod']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/layout/pie.php'; ?>
