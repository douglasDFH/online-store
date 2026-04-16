<?php require_once __DIR__ . '/layout/encabezado.php'; ?>
<div class="container mt-4">
    <h1 class="mb-4">Administracion de Cuentas y Clientes</h1>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>

    <form method="POST" action="index.php?pagina=admin_clientes" class="card card-body mb-4">
        <h5 class="mb-3">Nueva cuenta + cliente</h5>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Usuario</label>
                <input type="text" name="usuario" class="form-control" required>
            </div>
            <div class="form-group col-md-3">
                <label>Password</label>
                <input type="text" name="password" class="form-control" required>
            </div>
            <div class="form-group col-md-3">
                <label>CI</label>
                <input type="text" name="ci" class="form-control" required>
            </div>
            <div class="form-group col-md-3">
                <label>Nombres</label>
                <input type="text" name="nombres" class="form-control" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-2">
                <label>Ap. Paterno</label>
                <input type="text" name="apPaterno" class="form-control" required>
            </div>
            <div class="form-group col-md-2">
                <label>Ap. Materno</label>
                <input type="text" name="apMaterno" class="form-control" required>
            </div>
            <div class="form-group col-md-3">
                <label>Correo</label>
                <input type="email" name="correo" class="form-control" required>
            </div>
            <div class="form-group col-md-3">
                <label>Direccion</label>
                <input type="text" name="direccion" class="form-control" required>
            </div>
            <div class="form-group col-md-2">
                <label>Celular</label>
                <input type="text" name="nroCelular" class="form-control" required>
            </div>
        </div>
        <button class="btn btn-primary" type="submit">Guardar cliente</button>
    </form>

    <div class="row">
        <div class="col-md-5">
            <h5>Cuentas</h5>
            <ul class="list-group mb-3">
                <?php foreach ($cuentas as $cuenta): ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <span><?php echo htmlspecialchars($cuenta['usuario']); ?></span>
                        <small><?php echo htmlspecialchars($cuenta['password']); ?></small>
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($cliente['ci']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['nombres'] . ' ' . $cliente['apPaterno'] . ' ' . $cliente['apMaterno']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['correo']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['usuarioCuenta']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/layout/pie.php'; ?>
