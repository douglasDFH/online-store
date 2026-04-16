<?php require_once __DIR__ . '/layout/encabezado.php'; ?>
<div class="container mt-4">
    <h1 class="mb-4">Administracion de Productos y Stock</h1>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>

    <form method="POST" action="index.php?pagina=admin_productos" class="card card-body mb-4">
        <h5><?php echo !empty($productoEditar) ? 'Editar producto' : 'Nuevo producto'; ?></h5>
        <input type="hidden" name="accion" value="<?php echo !empty($productoEditar) ? 'editar_producto' : 'crear_producto'; ?>">
        <input type="hidden" name="id_producto" value="<?php echo !empty($productoEditar) ? (int)$productoEditar['id_producto'] : 0; ?>">
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo !empty($productoEditar) ? htmlspecialchars($productoEditar['nombre']) : ''; ?>" required>
            </div>
            <div class="form-group col-md-3">
                <label>Descripcion</label>
                <input type="text" name="descripcion" class="form-control" value="<?php echo !empty($productoEditar) ? htmlspecialchars($productoEditar['descripcion']) : ''; ?>" required>
            </div>
            <div class="form-group col-md-2">
                <label>Precio</label>
                <input type="number" name="precio" class="form-control" step="0.01" min="0.01" value="<?php echo !empty($productoEditar) ? htmlspecialchars($productoEditar['precio']) : ''; ?>" required>
            </div>
            <div class="form-group col-md-2">
                <label>Imagen</label>
                <input type="text" name="imagen" class="form-control" value="<?php echo !empty($productoEditar) ? htmlspecialchars($productoEditar['imagen']) : 'sudadera.png'; ?>" required>
            </div>
            <div class="form-group col-md-2">
                <label>Estado</label>
                <select name="estado" class="form-control">
                    <option value="activo" <?php echo (!empty($productoEditar) && $productoEditar['estado'] === 'activo') ? 'selected' : ''; ?>>activo</option>
                    <option value="inactivo" <?php echo (!empty($productoEditar) && $productoEditar['estado'] === 'inactivo') ? 'selected' : ''; ?>>inactivo</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Marca</label>
                <select name="codMarca" class="form-control" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($marcas as $marca): ?>
                        <option value="<?php echo (int)$marca['cod']; ?>" <?php echo (!empty($productoEditar) && (int)$productoEditar['codMarca'] === (int)$marca['cod']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($marca['nombre']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>Industria</label>
                <select name="codIndustria" class="form-control" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($industrias as $industria): ?>
                        <option value="<?php echo (int)$industria['cod']; ?>" <?php echo (!empty($productoEditar) && (int)$productoEditar['codIndustria'] === (int)$industria['cod']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($industria['nombre']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>Categoria</label>
                <select name="codCategoria" class="form-control" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?php echo (int)$categoria['cod']; ?>" <?php echo (!empty($productoEditar) && (int)$productoEditar['codCategoria'] === (int)$categoria['cod']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($categoria['nombre']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <button class="btn btn-primary" type="submit"><?php echo !empty($productoEditar) ? 'Actualizar producto' : 'Guardar producto'; ?></button>
        <?php if (!empty($productoEditar)): ?>
            <a href="index.php?pagina=admin_productos" class="btn btn-secondary mt-2">Cancelar edicion</a>
        <?php endif; ?>
    </form>

    <form method="POST" action="index.php?pagina=admin_productos" class="card card-body mb-4">
        <h5>Asignar stock por sucursal</h5>
        <input type="hidden" name="accion" value="guardar_stock">
        <div class="form-row">
            <div class="form-group col-md-5">
                <label>Producto</label>
                <select name="codProducto" class="form-control" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($productos as $producto): ?>
                        <option value="<?php echo (int)$producto['id_producto']; ?>"><?php echo htmlspecialchars($producto['nombre']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-md-5">
                <label>Sucursal</label>
                <select name="codSucursal" class="form-control" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($sucursales as $sucursal): ?>
                        <option value="<?php echo (int)$sucursal['cod']; ?>"><?php echo htmlspecialchars($sucursal['nombre']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-md-2">
                <label>Stock</label>
                <input type="number" name="stock" class="form-control" min="0" step="1" required>
            </div>
        </div>
        <button class="btn btn-success" type="submit">Guardar stock</button>
    </form>

    <h5>Lista de productos</h5>
    <div class="table-responsive mb-4">
        <table class="table table-bordered table-sm">
            <thead class="thead-light">
                <tr>
                    <th>Cod</th>
                    <th>Nombre</th>
                    <th>Categoria</th>
                    <th>Marca</th>
                    <th>Estado</th>
                    <th>Precio</th>
                    <th>Stock total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?php echo (int)$producto['id_producto']; ?></td>
                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($producto['categoria'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($producto['marca'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($producto['estado']); ?></td>
                        <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                        <td><?php echo (int)$producto['stock']; ?></td>
                        <td>
                            <a class="btn btn-warning btn-sm" href="index.php?pagina=admin_productos&editar_producto=<?php echo (int)$producto['id_producto']; ?>">Editar</a>
                            <a class="btn btn-danger btn-sm" href="index.php?pagina=admin_productos&eliminar_producto=<?php echo (int)$producto['id_producto']; ?>">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <h5>Detalle de stock por sucursal</h5>
    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead class="thead-light">
                <tr>
                    <th>Producto</th>
                    <th>Sucursal</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stocks as $stock): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($stock['producto']); ?></td>
                        <td><?php echo htmlspecialchars($stock['sucursal']); ?></td>
                        <td><?php echo htmlspecialchars($stock['stock']); ?></td>
                        <td>
                            <a class="btn btn-danger btn-sm" href="index.php?pagina=admin_productos&eliminar_stock_producto=<?php echo (int)$stock['codProducto']; ?>&eliminar_stock_sucursal=<?php echo (int)$stock['codSucursal']; ?>">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/layout/pie.php'; ?>
