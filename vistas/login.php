<main class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body">
                    <h1 class="card-title text-center mb-4">Iniciar Sesión</h1>

                    <?php if ($mensaje): ?>
                        <div class="alert alert-<?php echo htmlspecialchars($tipoMensaje); ?>" role="alert">
                            <?php echo htmlspecialchars($mensaje); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?pagina=login">
                        <div class="form-group">
                            <label for="usuario">Usuario:</label>
                            <input type="text" class="form-control" id="usuario" name="usuario" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
                    </form>

                    <hr>

                    <div class="alert alert-info" role="alert">
                        <strong>Credenciales:</strong><br>
                        <small>
                            Usa un usuario y contraseña existentes en la tabla Cuenta.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
