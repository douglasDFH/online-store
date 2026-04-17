<?php
require_once __DIR__ . '/../config/database.php';

class Producto {
    private $db;

    public function __construct() {
        $this->db = Database::conectar();
    }

    public function obtenerTodos() {
        $stmtSp = $this->db->prepare("CALL sp_listar_productos_con_stock_total()");
        if ($stmtSp) {
            $ok = $stmtSp->execute();
            if ($ok) {
                $resultado = $stmtSp->get_result();
                $datos = $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
                $stmtSp->close();
                $this->limpiarResultadosPendientes();
                return $datos;
            }
            $stmtSp->close();
            $this->limpiarResultadosPendientes();
        }

        $sql = "SELECT
                    p.cod AS id_producto,
                    p.nombre,
                    p.descripcion,
                    p.precio,
                    p.imagen,
                    p.estado,
                    p.codMarca,
                    p.codIndustria,
                    p.codCategoria,
                    COALESCE(SUM(CAST(dps.stock AS UNSIGNED)), 0) AS stock,
                    m.nombre AS marca,
                    c.nombre AS categoria,
                    i.nombre AS industria
                FROM `Producto` p
                LEFT JOIN `DetalleProductoSucursal` dps ON dps.codProducto = p.cod
                LEFT JOIN `Marca` m ON m.cod = p.codMarca
                LEFT JOIN `Categoria` c ON c.cod = p.codCategoria
                LEFT JOIN `Industria` i ON i.cod = p.codIndustria
                GROUP BY p.cod, p.nombre, p.descripcion, p.precio, p.imagen, p.estado, p.codMarca, p.codIndustria, p.codCategoria, m.nombre, c.nombre, i.nombre
                ORDER BY p.cod DESC";

        $resultado = $this->db->query($sql);
        if (!$resultado) {
            return [];
        }

        $productos = [];
        while ($fila = $resultado->fetch_assoc()) {
            $productos[] = $fila;
        }

        return $productos;
    }

    public function obtenerPorId($id) {
        $id = (int)$id;

        $stmtSp = $this->db->prepare("CALL sp_obtener_producto_por_id(?)");
        if ($stmtSp) {
            $stmtSp->bind_param("i", $id);
            $ok = $stmtSp->execute();
            if ($ok) {
                $resultado = $stmtSp->get_result();
                $fila = $resultado ? $resultado->fetch_assoc() : null;
                $stmtSp->close();
                $this->limpiarResultadosPendientes();
                return $fila ?: null;
            }
            $stmtSp->close();
            $this->limpiarResultadosPendientes();
        }

        $sql = "SELECT
                    p.cod AS id_producto,
                    p.nombre,
                    p.descripcion,
                    p.precio,
                    p.imagen,
                    p.estado,
                    p.codMarca,
                    p.codIndustria,
                    p.codCategoria,
                    COALESCE(SUM(CAST(dps.stock AS UNSIGNED)), 0) AS stock,
                    m.nombre AS marca,
                    c.nombre AS categoria,
                    i.nombre AS industria
                FROM `Producto` p
                LEFT JOIN `DetalleProductoSucursal` dps ON dps.codProducto = p.cod
                LEFT JOIN `Marca` m ON m.cod = p.codMarca
                LEFT JOIN `Categoria` c ON c.cod = p.codCategoria
                LEFT JOIN `Industria` i ON i.cod = p.codIndustria
                WHERE p.cod = ?
                GROUP BY p.cod, p.nombre, p.descripcion, p.precio, p.imagen, p.estado, p.codMarca, p.codIndustria, p.codCategoria, m.nombre, c.nombre, i.nombre";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if (!$resultado || $resultado->num_rows === 0) {
            return null;
        }

        return $resultado->fetch_assoc();
    }

    public function agregar($nombre, $descripcion, $precio, $imagen, $codMarca, $codIndustria, $codCategoria, $estado = 'activo') {
        $precio = (float)$precio;
        $codMarca = (int)$codMarca;
        $codIndustria = (int)$codIndustria;
        $codCategoria = (int)$codCategoria;

        $stmtSp = $this->db->prepare("CALL sp_crear_producto(?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmtSp) {
            $stmtSp->bind_param("ssdssiii", $nombre, $descripcion, $precio, $imagen, $estado, $codMarca, $codIndustria, $codCategoria);
            $ok = $stmtSp->execute();
            $stmtSp->close();
            $this->limpiarResultadosPendientes();
            if ($ok) {
                return true;
            }
        }

        $sql = "INSERT INTO `Producto` (nombre, descripcion, precio, imagen, estado, codMarca, codIndustria, codCategoria)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ssdssiii", $nombre, $descripcion, $precio, $imagen, $estado, $codMarca, $codIndustria, $codCategoria);
        return $stmt->execute();
    }

    public function actualizar($id, $nombre, $descripcion, $precio, $imagen, $codMarca, $codIndustria, $codCategoria, $estado = 'activo') {
        $id = (int)$id;
        $precio = (float)$precio;
        $codMarca = (int)$codMarca;
        $codIndustria = (int)$codIndustria;
        $codCategoria = (int)$codCategoria;

        $stmtSp = $this->db->prepare("CALL sp_actualizar_producto(?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmtSp) {
            $stmtSp->bind_param("issdssiii", $id, $nombre, $descripcion, $precio, $imagen, $estado, $codMarca, $codIndustria, $codCategoria);
            $ok = $stmtSp->execute();
            $stmtSp->close();
            $this->limpiarResultadosPendientes();
            if ($ok) {
                return true;
            }
        }

        $sql = "UPDATE `Producto`
                SET nombre = ?, descripcion = ?, precio = ?, imagen = ?, estado = ?, codMarca = ?, codIndustria = ?, codCategoria = ?
                WHERE cod = ?";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ssdssiiii", $nombre, $descripcion, $precio, $imagen, $estado, $codMarca, $codIndustria, $codCategoria, $id);
        return $stmt->execute();
    }

    public function eliminar($id) {
        $id = (int)$id;

        $stmtSp = $this->db->prepare("CALL sp_eliminar_producto(?)");
        if ($stmtSp) {
            $stmtSp->bind_param("i", $id);
            $ok = $stmtSp->execute();
            $stmtSp->close();
            $this->limpiarResultadosPendientes();
            if ($ok) {
                return true;
            }
        }

        $sql = "DELETE FROM `Producto` WHERE cod = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    private function limpiarResultadosPendientes() {
        while ($this->db->more_results() && $this->db->next_result()) {
            $resultado = $this->db->use_result();
            if ($resultado instanceof mysqli_result) {
                $resultado->free();
            }
        }
    }
}
