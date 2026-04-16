<?php require_once __DIR__ . '/layout/encabezado.php'; ?>
<div class="container mt-4">
    <h1 class="mb-4">Reporte de Ventas</h1>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Nro</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Total Items</th>
                    <th>Detalle</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ventas as $venta): ?>
                    <tr>
                        <td><?php echo (int)$venta['nro']; ?></td>
                        <td><?php echo htmlspecialchars($venta['fechaHora']); ?></td>
                        <td><?php echo htmlspecialchars($venta['cliente']); ?> (<?php echo htmlspecialchars($venta['ciCliente']); ?>)</td>
                        <td><?php echo (int)$venta['totalItems']; ?></td>
                        <td>
                            <?php if (!empty($detalles[$venta['nro']])): ?>
                                <ul class="mb-0 pl-3">
                                    <?php foreach ($detalles[$venta['nro']] as $detalle): ?>
                                        <li>
                                            <?php echo htmlspecialchars($detalle['producto']); ?>
                                            | Cant: <?php echo (int)$detalle['cant']; ?>
                                            | Precio: $<?php echo number_format($detalle['precio'], 2); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <span class="text-muted">Sin detalle</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/layout/pie.php'; ?>
