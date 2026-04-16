<?php require_once __DIR__ . '/layout/encabezado.php'; ?>
<div class="container mt-4">
    <h1 class="mb-4">Administracion de Sucursales</h1>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>

    <form method="POST" action="index.php?pagina=admin_sucursales" class="card card-body mb-4">
        <input type="hidden" name="accion" value="<?php echo !empty($sucursalEditar) ? 'editar' : 'crear'; ?>">
        <input type="hidden" name="cod" value="<?php echo !empty($sucursalEditar) ? (int)$sucursalEditar['cod'] : 0; ?>">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo !empty($sucursalEditar) ? htmlspecialchars($sucursalEditar['nombre']) : ''; ?>" required>
            </div>
            <div class="form-group col-md-5">
                <label>Direccion</label>
                <input type="text" name="direccion" class="form-control" value="<?php echo !empty($sucursalEditar) ? htmlspecialchars($sucursalEditar['direccion']) : ''; ?>" required>
            </div>
            <div class="form-group col-md-3">
                <label>Telefono</label>
                <input type="text" name="nroTelefono" class="form-control" value="<?php echo !empty($sucursalEditar) ? htmlspecialchars($sucursalEditar['nroTelefono']) : ''; ?>" required>
            </div>
        </div>
        <button class="btn btn-primary" type="submit"><?php echo !empty($sucursalEditar) ? 'Actualizar sucursal' : 'Guardar sucursal'; ?></button>
        <?php if (!empty($sucursalEditar)): ?>
            <a href="index.php?pagina=admin_sucursales" class="btn btn-secondary mt-2">Cancelar edicion</a>
        <?php endif; ?>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Cod</th>
                    <th>Nombre</th>
                    <th>Direccion</th>
                    <th>Telefono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sucursales as $sucursal): ?>
                    <tr>
                        <td><?php echo (int)$sucursal['cod']; ?></td>
                        <td><?php echo htmlspecialchars($sucursal['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($sucursal['direccion']); ?></td>
                        <td><?php echo htmlspecialchars($sucursal['nroTelefono']); ?></td>
                        <td>
                            <a class="btn btn-warning btn-sm" href="index.php?pagina=admin_sucursales&editar=<?php echo (int)$sucursal['cod']; ?>">Editar</a>
                            <a class="btn btn-danger btn-sm" href="index.php?pagina=admin_sucursales&eliminar=<?php echo (int)$sucursal['cod']; ?>">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/layout/pie.php'; ?>
