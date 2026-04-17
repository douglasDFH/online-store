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

            $nroVenta = $this->crearNotaVenta($ciDemo);

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

                if (!$this->insertarDetalleVenta($nroVenta, $codProducto, $item, $cantidad)) {
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

    private function crearNotaVenta($ciCliente) {
        $stmt = $this->db->prepare("CALL sp_crear_nota_venta(?, @p_nro_venta)");
        if ($stmt) {
            $stmt->bind_param('s', $ciCliente);
            $ok = $stmt->execute();
            $stmt->close();
            $this->limpiarResultadosPendientes();

            if ($ok) {
                $resultado = $this->db->query("SELECT @p_nro_venta AS nro");
                if ($resultado) {
                    $fila = $resultado->fetch_assoc();
                    $nro = isset($fila['nro']) ? (int)$fila['nro'] : 0;
                    if ($nro > 0) {
                        return $nro;
                    }
                }
            }
        }

        // Fallback para entornos donde los procedimientos aun no fueron instalados.
        $nroVenta = $this->obtenerSiguienteNumeroVenta();

        $sqlVenta = "INSERT INTO `NotaVenta` (nro, fechaHora, ciCliente) VALUES (?, NOW(), ?)";
        $stmtVenta = $this->db->prepare($sqlVenta);
        if (!$stmtVenta) {
            throw new Exception('No se pudo preparar la nota de venta.');
        }

        $stmtVenta->bind_param('is', $nroVenta, $ciCliente);
        if (!$stmtVenta->execute()) {
            throw new Exception('No se pudo registrar la nota de venta.');
        }

        return $nroVenta;
    }

    private function insertarDetalleVenta($nroVenta, $codProducto, $item, $cantidad) {
        $stmt = $this->db->prepare("CALL sp_insertar_detalle_venta(?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param('iiii', $nroVenta, $codProducto, $item, $cantidad);
            $ok = $stmt->execute();
            $stmt->close();
            $this->limpiarResultadosPendientes();
            if ($ok) {
                return true;
            }
        }

        $sqlDetalle = "INSERT INTO `DetalleNotaVenta` (nroNotaVenta, codProducto, item, cant) VALUES (?, ?, ?, ?)";
        $stmtDetalle = $this->db->prepare($sqlDetalle);
        if (!$stmtDetalle) {
            return false;
        }

        $stmtDetalle->bind_param('iiii', $nroVenta, $codProducto, $item, $cantidad);
        return $stmtDetalle->execute();
    }

    private function limpiarResultadosPendientes() {
        while ($this->db->more_results() && $this->db->next_result()) {
            $resultado = $this->db->use_result();
            if ($resultado instanceof mysqli_result) {
                $resultado->free();
            }
        }
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
