<?php
require_once __DIR__ . '/../config/database.php';

class Venta {
    private $db;

    public function __construct() {
        $this->db = Database::conectar();
    }

    public function registrarVenta($carrito) {
        if (empty($carrito)) {
            return false;
        }

        $this->db->begin_transaction();

        try {
            $usuarioDemo = 'cliente_demo';
            $passwordDemo = '12345';
            $ciDemo = '0000000000';

            $this->asegurarCuentaDemo($usuarioDemo, $passwordDemo);
            $this->asegurarClienteDemo($ciDemo, $usuarioDemo);

            $nroVenta = $this->obtenerSiguienteNumeroVenta();

            $sqlVenta = "INSERT INTO `NotaVenta` (nro, fechaHora, ciCliente) VALUES (?, NOW(), ?)";
            $stmtVenta = $this->db->prepare($sqlVenta);
            if (!$stmtVenta) {
                throw new Exception('No se pudo preparar la nota de venta.');
            }

            $stmtVenta->bind_param('is', $nroVenta, $ciDemo);
            if (!$stmtVenta->execute()) {
                throw new Exception('No se pudo registrar la nota de venta.');
            }

            $sqlDetalle = "INSERT INTO `DetalleNotaVenta` (nroNotaVenta, codProducto, item, cant) VALUES (?, ?, ?, ?)";
            $stmtDetalle = $this->db->prepare($sqlDetalle);
            if (!$stmtDetalle) {
                throw new Exception('No se pudo preparar el detalle de venta.');
            }

            $item = 1;
            foreach ($carrito as $linea) {
                $codProducto = isset($linea['id_producto']) ? (int)$linea['id_producto'] : 0;
                $cantidad = isset($linea['cantidad']) ? (int)$linea['cantidad'] : 0;

                if ($codProducto <= 0 || $cantidad <= 0) {
                    continue;
                }

                if (!$this->existeProducto($codProducto)) {
                    continue;
                }

                $stmtDetalle->bind_param('iiii', $nroVenta, $codProducto, $item, $cantidad);
                if (!$stmtDetalle->execute()) {
                    throw new Exception('No se pudo registrar el detalle de venta.');
                }

                $item++;
            }

            if ($item === 1) {
                throw new Exception('No hay productos validos para registrar.');
            }

            $this->db->commit();
            return $nroVenta;
        } catch (Throwable $e) {
            $this->db->rollback();
            return false;
        }
    }

    private function asegurarCuentaDemo($usuario, $password) {
        $sqlBuscar = "SELECT usuario FROM `Cuenta` WHERE usuario = ? LIMIT 1";
        $stmtBuscar = $this->db->prepare($sqlBuscar);
        if (!$stmtBuscar) {
            throw new Exception('No se pudo verificar la cuenta demo.');
        }

        $stmtBuscar->bind_param('s', $usuario);
        $stmtBuscar->execute();
        $resultado = $stmtBuscar->get_result();
        if ($resultado && $resultado->num_rows > 0) {
            return;
        }

        $sqlInsertar = "INSERT INTO `Cuenta` (usuario, password) VALUES (?, ?)";
        $stmtInsertar = $this->db->prepare($sqlInsertar);
        if (!$stmtInsertar) {
            throw new Exception('No se pudo crear la cuenta demo.');
        }

        $stmtInsertar->bind_param('ss', $usuario, $password);
        if (!$stmtInsertar->execute()) {
            throw new Exception('No se pudo crear la cuenta demo.');
        }
    }

    private function asegurarClienteDemo($ci, $usuarioCuenta) {
        $sqlBuscar = "SELECT ci FROM `Cliente` WHERE ci = ? LIMIT 1";
        $stmtBuscar = $this->db->prepare($sqlBuscar);
        if (!$stmtBuscar) {
            throw new Exception('No se pudo verificar el cliente demo.');
        }

        $stmtBuscar->bind_param('s', $ci);
        $stmtBuscar->execute();
        $resultado = $stmtBuscar->get_result();
        if ($resultado && $resultado->num_rows > 0) {
            return;
        }

        $nombres = 'Consumidor';
        $apPaterno = 'Final';
        $apMaterno = 'Demo';
        $correo = 'demo@tienda.local';
        $direccion = 'Sin direccion';
        $nroCelular = '00000000';

        $sqlInsertar = "INSERT INTO `Cliente` (ci, nombres, apPaterno, apMaterno, correo, direccion, nroCelular, usuarioCuenta)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtInsertar = $this->db->prepare($sqlInsertar);
        if (!$stmtInsertar) {
            throw new Exception('No se pudo crear el cliente demo.');
        }

        $stmtInsertar->bind_param('ssssssss', $ci, $nombres, $apPaterno, $apMaterno, $correo, $direccion, $nroCelular, $usuarioCuenta);
        if (!$stmtInsertar->execute()) {
            throw new Exception('No se pudo crear el cliente demo.');
        }
    }

    private function obtenerSiguienteNumeroVenta() {
        $sql = "SELECT COALESCE(MAX(nro), 0) + 1 AS siguiente FROM `NotaVenta`";
        $resultado = $this->db->query($sql);
        if (!$resultado) {
            throw new Exception('No se pudo calcular el numero de venta.');
        }

        $fila = $resultado->fetch_assoc();
        return (int)$fila['siguiente'];
    }

    private function existeProducto($codProducto) {
        $sql = "SELECT cod FROM `Producto` WHERE cod = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('i', $codProducto);
        $stmt->execute();
        $resultado = $stmt->get_result();

        return $resultado && $resultado->num_rows > 0;
    }
}
