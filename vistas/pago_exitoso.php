<?php require_once __DIR__ . '/layout/encabezado.php'; ?>
<div class="container mt-5">
    <div class="alert alert-success text-center">
        <h1>¡Gracias por tu compra!</h1>
        <p>Tu pedido ha sido procesado exitosamente. Pronto recibirás un correo con los detalles de tu pedido.</p>
        <?php if (!empty($nroVenta)): ?>
            <p><strong>Número de venta:</strong> <?php echo (int)$nroVenta; ?></p>
        <?php endif; ?>
        <a href="index.php?pagina=inicio" class="btn btn-primary mt-3">Volver al Inicio</a>
    </div>
</div>
<?php require_once __DIR__ . '/layout/pie.php'; ?>
