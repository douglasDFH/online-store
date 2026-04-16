<?php require_once __DIR__ . '/layout/encabezado.php'; ?>
<div class="container mt-4">
    <h1 class="mb-4">Administracion de Cuentas y Clientes</h1>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>

    <form method="POST" action="index.php?pagina=admin_clientes" class="card card-body mb-4">
        <input type="hidden" name="accion" value="<?php echo !empty($clienteEditar) ? 'editar' : 'crear'; ?>">
        <h5 class="mb-3"><?php echo !empty($clienteEditar) ? 'Editar cliente' : 'Nueva cuenta + cliente'; ?></h5>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Usuario</label>
                <?php if (!empty($clienteEditar)): ?>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($clienteEditar['usuarioCuenta']); ?>" readonly>
                    <input type="hidden" name="usuarioCuenta" value="<?php echo htmlspecialchars($clienteEditar['usuarioCuenta']); ?>">
                <?php else: ?>
                    <input type="text" name="usuario" class="form-control" required>
                <?php endif; ?>
            </div>
            <div class="form-group col-md-3">
                <label>Password</label>
                <input type="text" name="password" class="form-control" <?php echo empty($clienteEditar) ? 'required' : ''; ?> placeholder="<?php echo !empty($clienteEditar) ? 'Opcional para cambiar' : ''; ?>">
            </div>
            <div class="form-group col-md-3">
                <label>CI</label>
                <?php if (!empty($clienteEditar)): ?>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($clienteEditar['ci']); ?>" readonly>
                    <input type="hidden" name="ci" value="<?php echo htmlspecialchars($clienteEditar['ci']); ?>">
                <?php else: ?>
                    <input type="text" name="ci" class="form-control" required>
                <?php endif; ?>
            </div>
            <div class="form-group col-md-3">
                <label>Nombres</label>
                <input type="text" name="nombres" class="form-control" value="<?php echo !empty($clienteEditar) ? htmlspecialchars($clienteEditar['nombres']) : ''; ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-2">
                <label>Ap. Paterno</label>
                <input type="text" name="apPaterno" class="form-control" value="<?php echo !empty($clienteEditar) ? htmlspecialchars($clienteEditar['apPaterno']) : ''; ?>" required>
            </div>
            <div class="form-group col-md-2">
                <label>Ap. Materno</label>
                <input type="text" name="apMaterno" class="form-control" value="<?php echo !empty($clienteEditar) ? htmlspecialchars($clienteEditar['apMaterno']) : ''; ?>" required>
            </div>
            <div class="form-group col-md-3">
                <label>Correo</label>
                <input type="email" name="correo" class="form-control" value="<?php echo !empty($clienteEditar) ? htmlspecialchars($clienteEditar['correo']) : ''; ?>" required>
            </div>
            <div class="form-group col-md-3">
                <label>Direccion</label>
                <input type="text" name="direccion" class="form-control" value="<?php echo !empty($clienteEditar) ? htmlspecialchars($clienteEditar['direccion']) : ''; ?>" required>
            </div>
            <div class="form-group col-md-2">
                <label>Celular</label>
                <input type="text" name="nroCelular" class="form-control" value="<?php echo !empty($clienteEditar) ? htmlspecialchars($clienteEditar['nroCelular']) : ''; ?>" required>
            </div>
        </div>
        <button class="btn btn-primary" type="submit"><?php echo !empty($clienteEditar) ? 'Actualizar cliente' : 'Guardar cliente'; ?></button>
        <?php if (!empty($clienteEditar)): ?>
            <a href="index.php?pagina=admin_clientes" class="btn btn-secondary mt-2">Cancelar edicion</a>
        <?php endif; ?>
    </form>

    <div class="row">
        <div class="col-md-5">
            <h5>Cuentas</h5>
            <ul class="list-group mb-3">
                <?php foreach ($cuentas as $cuenta): ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <span><?php echo htmlspecialchars($cuenta['usuario']); ?></span>
                        <span>
                            <small class="mr-2"><?php echo htmlspecialchars($cuenta['password']); ?></small>
                            <?php if (in_array($cuenta['usuario'], ['cliente_demo', 'admin'], true)): ?>
                                <span class="badge badge-secondary">Protegido</span>
                            <?php else: ?>
                                <a class="btn btn-danger btn-sm btn-delete"
                                   href="#"
                                   data-toggle="modal"
                                   data-target="#confirmDeleteModal"
                                   data-url="index.php?pagina=admin_clientes&eliminar_cuenta=<?php echo urlencode($cuenta['usuario']); ?>"
                                   data-label="la cuenta <?php echo htmlspecialchars($cuenta['usuario'], ENT_QUOTES); ?>">Eliminar</a>
                            <?php endif; ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="col-md-7">
            <h5>Clientes</h5>
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>CI</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Usuario</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($cliente['ci']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['nombres'] . ' ' . $cliente['apPaterno'] . ' ' . $cliente['apMaterno']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['correo']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['usuarioCuenta']); ?></td>
                                <td>
                                    <a class="btn btn-warning btn-sm" href="index.php?pagina=admin_clientes&editar_ci=<?php echo urlencode($cliente['ci']); ?>&editar_usuario=<?php echo urlencode($cliente['usuarioCuenta']); ?>">Editar</a>
                                    <?php if (in_array($cliente['usuarioCuenta'], ['cliente_demo', 'admin'], true)): ?>
                                        <span class="badge badge-secondary">Protegido</span>
                                    <?php else: ?>
                                        <a class="btn btn-danger btn-sm btn-delete"
                                           href="#"
                                           data-toggle="modal"
                                           data-target="#confirmDeleteModal"
                                           data-url="index.php?pagina=admin_clientes&eliminar_cliente_ci=<?php echo urlencode($cliente['ci']); ?>&eliminar_cliente_usuario=<?php echo urlencode($cliente['usuarioCuenta']); ?>"
                                           data-label="el cliente <?php echo htmlspecialchars($cliente['nombres'] . ' ' . $cliente['apPaterno'], ENT_QUOTES); ?> y su cuenta asociada">Eliminar</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar eliminacion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="confirmDeleteModalBody">
                ¿Estas seguro de eliminar este registro?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <a href="#" class="btn btn-danger" id="confirmDeleteButton">Eliminar</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('confirmDeleteModal');
    var btnConfirmar = document.getElementById('confirmDeleteButton');
    var cuerpoModal = document.getElementById('confirmDeleteModalBody');

    $('#confirmDeleteModal').on('show.bs.modal', function (event) {
        var trigger = event.relatedTarget;
        if (!trigger) {
            btnConfirmar.setAttribute('href', '#');
            cuerpoModal.textContent = '¿Estas seguro de eliminar este registro?';
            return;
        }

        var url = trigger.getAttribute('data-url') || '#';
        var etiqueta = trigger.getAttribute('data-label') || 'este registro';
        btnConfirmar.setAttribute('href', url);
        cuerpoModal.textContent = '¿Estas seguro de eliminar ' + etiqueta + '?';
    });

    modal.addEventListener('hidden.bs.modal', function () {
        btnConfirmar.setAttribute('href', '#');
    });
});
</script>
<?php require_once __DIR__ . '/layout/pie.php'; ?>
