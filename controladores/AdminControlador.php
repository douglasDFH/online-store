<?php
require_once __DIR__ . '/../modelos/Marca.php';
require_once __DIR__ . '/../modelos/Categoria.php';
require_once __DIR__ . '/../modelos/Industria.php';
require_once __DIR__ . '/../modelos/Sucursal.php';
require_once __DIR__ . '/../modelos/Cuenta.php';
require_once __DIR__ . '/../modelos/Cliente.php';
require_once __DIR__ . '/../modelos/Producto.php';
require_once __DIR__ . '/../modelos/DetalleProductoSucursal.php';
require_once __DIR__ . '/../modelos/NotaVenta.php';

class AdminControlador {
    public function catalogos() {
        $marcaModel = new Marca();
        $categoriaModel = new Categoria();
        $industriaModel = new Industria();
        $mensaje = null;
        $edicion = [
            'tipo' => '',
            'cod' => 0,
            'nombre' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accion = $_POST['accion'] ?? 'crear';
            $tipo = $_POST['tipo'] ?? '';
            $cod = (int)($_POST['cod'] ?? 0);
            $nombre = trim($_POST['nombre'] ?? '');

            if ($nombre !== '') {
                if ($tipo === 'marca') {
                    if ($accion === 'editar' && $cod > 0) {
                        $marcaModel->actualizar($cod, $nombre);
                        $mensaje = 'Marca actualizada correctamente.';
                    } else {
                        $marcaModel->crear($nombre);
                        $mensaje = 'Marca creada correctamente.';
                    }
                } elseif ($tipo === 'categoria') {
                    if ($accion === 'editar' && $cod > 0) {
                        $categoriaModel->actualizar($cod, $nombre);
                        $mensaje = 'Categoria actualizada correctamente.';
                    } else {
                        $categoriaModel->crear($nombre);
                        $mensaje = 'Categoria creada correctamente.';
                    }
                } elseif ($tipo === 'industria') {
                    if ($accion === 'editar' && $cod > 0) {
                        $industriaModel->actualizar($cod, $nombre);
                        $mensaje = 'Industria actualizada correctamente.';
                    } else {
                        $industriaModel->crear($nombre);
                        $mensaje = 'Industria creada correctamente.';
                    }
                }
            }
        }

        if (isset($_GET['eliminar_tipo'], $_GET['cod'])) {
            $tipo = $_GET['eliminar_tipo'];
            $cod = (int)$_GET['cod'];

            if ($tipo === 'marca') {
                $marcaModel->eliminar($cod);
            } elseif ($tipo === 'categoria') {
                $categoriaModel->eliminar($cod);
            } elseif ($tipo === 'industria') {
                $industriaModel->eliminar($cod);
            }

            header('Location: index.php?pagina=admin_catalogos');
            exit();
        }

        $marcas = $marcaModel->obtenerTodos();
        $categorias = $categoriaModel->obtenerTodos();
        $industrias = $industriaModel->obtenerTodos();

        if (isset($_GET['editar_tipo'], $_GET['cod'])) {
            $tipo = $_GET['editar_tipo'];
            $cod = (int)$_GET['cod'];

            if ($tipo === 'marca') {
                foreach ($marcas as $fila) {
                    if ((int)$fila['cod'] === $cod) {
                        $edicion = ['tipo' => 'marca', 'cod' => $cod, 'nombre' => $fila['nombre']];
                        break;
                    }
                }
            }

            if ($tipo === 'categoria') {
                foreach ($categorias as $fila) {
                    if ((int)$fila['cod'] === $cod) {
                        $edicion = ['tipo' => 'categoria', 'cod' => $cod, 'nombre' => $fila['nombre']];
                        break;
                    }
                }
            }

            if ($tipo === 'industria') {
                foreach ($industrias as $fila) {
                    if ((int)$fila['cod'] === $cod) {
                        $edicion = ['tipo' => 'industria', 'cod' => $cod, 'nombre' => $fila['nombre']];
                        break;
                    }
                }
            }
        }

        $titulo = 'Administracion - Catalogos';
        require_once __DIR__ . '/../vistas/admin_catalogos.php';
    }

    public function sucursales() {
        $sucursalModel = new Sucursal();
        $mensaje = null;
        $sucursalEditar = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accion = $_POST['accion'] ?? 'crear';
            $cod = (int)($_POST['cod'] ?? 0);
            $nombre = trim($_POST['nombre'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');
            $nroTelefono = trim($_POST['nroTelefono'] ?? '');

            if ($nombre !== '' && $direccion !== '' && $nroTelefono !== '') {
                if ($accion === 'editar' && $cod > 0) {
                    $sucursalModel->actualizar($cod, $nombre, $direccion, $nroTelefono);
                    $mensaje = 'Sucursal actualizada correctamente.';
                } else {
                    $sucursalModel->crear($nombre, $direccion, $nroTelefono);
                    $mensaje = 'Sucursal creada correctamente.';
                }
            }
        }

        if (isset($_GET['eliminar'])) {
            $sucursalModel->eliminar((int)$_GET['eliminar']);
            header('Location: index.php?pagina=admin_sucursales');
            exit();
        }

        if (isset($_GET['editar'])) {
            $sucursalEditar = $sucursalModel->obtenerPorCod((int)$_GET['editar']);
        }

        $sucursales = $sucursalModel->obtenerTodas();
        $titulo = 'Administracion - Sucursales';
        require_once __DIR__ . '/../vistas/admin_sucursales.php';
    }

    public function clientes() {
        $cuentaModel = new Cuenta();
        $clienteModel = new Cliente();
        $mensaje = null;
        $clienteEditar = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accion = $_POST['accion'] ?? 'crear';

            if ($accion === 'crear') {
                $usuario = trim($_POST['usuario'] ?? '');
                $password = trim($_POST['password'] ?? '');
                $ci = trim($_POST['ci'] ?? '');
                $nombres = trim($_POST['nombres'] ?? '');
                $apPaterno = trim($_POST['apPaterno'] ?? '');
                $apMaterno = trim($_POST['apMaterno'] ?? '');
                $correo = trim($_POST['correo'] ?? '');
                $direccion = trim($_POST['direccion'] ?? '');
                $nroCelular = trim($_POST['nroCelular'] ?? '');

                if ($usuario !== '' && $password !== '' && $ci !== '' && $nombres !== '' && $apPaterno !== '' && $apMaterno !== '' && $correo !== '' && $direccion !== '' && $nroCelular !== '') {
                    $okCuenta = $cuentaModel->crear($usuario, $password);
                    if ($okCuenta) {
                        $clienteModel->crear($ci, $nombres, $apPaterno, $apMaterno, $correo, $direccion, $nroCelular, $usuario);
                        $mensaje = 'Cliente y cuenta creados correctamente.';
                    } else {
                        $mensaje = 'No se pudo crear la cuenta (puede existir ya el usuario).';
                    }
                }
            }

            if ($accion === 'editar') {
                $usuarioCuenta = trim($_POST['usuarioCuenta'] ?? '');
                $ci = trim($_POST['ci'] ?? '');
                $password = trim($_POST['password'] ?? '');
                $nombres = trim($_POST['nombres'] ?? '');
                $apPaterno = trim($_POST['apPaterno'] ?? '');
                $apMaterno = trim($_POST['apMaterno'] ?? '');
                $correo = trim($_POST['correo'] ?? '');
                $direccion = trim($_POST['direccion'] ?? '');
                $nroCelular = trim($_POST['nroCelular'] ?? '');

                if ($usuarioCuenta !== '' && $ci !== '' && $nombres !== '' && $apPaterno !== '' && $apMaterno !== '' && $correo !== '' && $direccion !== '' && $nroCelular !== '') {
                    $clienteModel->actualizar($ci, $usuarioCuenta, $nombres, $apPaterno, $apMaterno, $correo, $direccion, $nroCelular);
                    if ($password !== '') {
                        $cuentaModel->actualizarPassword($usuarioCuenta, $password);
                    }
                    $mensaje = 'Cliente actualizado correctamente.';
                }
            }
        }

        if (isset($_GET['editar_ci'], $_GET['editar_usuario'])) {
            $clienteEditar = $clienteModel->obtenerPorClave($_GET['editar_ci'], $_GET['editar_usuario']);
            if ($clienteEditar) {
                $clienteEditar['password'] = '';
            }
        }

        $cuentas = $cuentaModel->obtenerTodas();
        $clientes = $clienteModel->obtenerTodos();

        $titulo = 'Administracion - Clientes';
        require_once __DIR__ . '/../vistas/admin_clientes.php';
    }

    public function productos() {
        $productoModel = new Producto();
        $marcaModel = new Marca();
        $categoriaModel = new Categoria();
        $industriaModel = new Industria();
        $sucursalModel = new Sucursal();
        $stockModel = new DetalleProductoSucursal();
        $mensaje = null;
        $productoEditar = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accion = $_POST['accion'] ?? '';

            if ($accion === 'crear_producto') {
                $nombre = trim($_POST['nombre'] ?? '');
                $descripcion = trim($_POST['descripcion'] ?? '');
                $precio = (float)($_POST['precio'] ?? 0);
                $imagen = trim($_POST['imagen'] ?? 'sudadera.png');
                $estado = trim($_POST['estado'] ?? 'activo');
                $codMarca = (int)($_POST['codMarca'] ?? 0);
                $codIndustria = (int)($_POST['codIndustria'] ?? 0);
                $codCategoria = (int)($_POST['codCategoria'] ?? 0);

                if ($nombre !== '' && $descripcion !== '' && $precio > 0 && $codMarca > 0 && $codIndustria > 0 && $codCategoria > 0) {
                    $productoModel->agregar($nombre, $descripcion, $precio, $imagen, $codMarca, $codIndustria, $codCategoria, $estado);
                    $mensaje = 'Producto creado correctamente.';
                }
            }

            if ($accion === 'editar_producto') {
                $idProducto = (int)($_POST['id_producto'] ?? 0);
                $nombre = trim($_POST['nombre'] ?? '');
                $descripcion = trim($_POST['descripcion'] ?? '');
                $precio = (float)($_POST['precio'] ?? 0);
                $imagen = trim($_POST['imagen'] ?? 'sudadera.png');
                $estado = trim($_POST['estado'] ?? 'activo');
                $codMarca = (int)($_POST['codMarca'] ?? 0);
                $codIndustria = (int)($_POST['codIndustria'] ?? 0);
                $codCategoria = (int)($_POST['codCategoria'] ?? 0);

                if ($idProducto > 0 && $nombre !== '' && $descripcion !== '' && $precio > 0 && $codMarca > 0 && $codIndustria > 0 && $codCategoria > 0) {
                    $productoModel->actualizar($idProducto, $nombre, $descripcion, $precio, $imagen, $codMarca, $codIndustria, $codCategoria, $estado);
                    $mensaje = 'Producto actualizado correctamente.';
                }
            }

            if ($accion === 'guardar_stock') {
                $codProducto = (int)($_POST['codProducto'] ?? 0);
                $codSucursal = (int)($_POST['codSucursal'] ?? 0);
                $stock = trim($_POST['stock'] ?? '0');

                if ($codProducto > 0 && $codSucursal > 0) {
                    $stockModel->guardarStock($codProducto, $codSucursal, $stock);
                    $mensaje = 'Stock guardado correctamente.';
                }
            }
        }

        if (isset($_GET['eliminar_producto'])) {
            $productoModel->eliminar((int)$_GET['eliminar_producto']);
            header('Location: index.php?pagina=admin_productos');
            exit();
        }

        if (isset($_GET['eliminar_stock_producto'], $_GET['eliminar_stock_sucursal'])) {
            $stockModel->eliminar((int)$_GET['eliminar_stock_producto'], (int)$_GET['eliminar_stock_sucursal']);
            header('Location: index.php?pagina=admin_productos');
            exit();
        }

        if (isset($_GET['editar_producto'])) {
            $productoEditar = $productoModel->obtenerPorId((int)$_GET['editar_producto']);
        }

        $productos = $productoModel->obtenerTodos();
        $marcas = $marcaModel->obtenerTodos();
        $categorias = $categoriaModel->obtenerTodos();
        $industrias = $industriaModel->obtenerTodos();
        $sucursales = $sucursalModel->obtenerTodas();
        $stocks = $stockModel->obtenerTodos();

        $titulo = 'Administracion - Productos';
        require_once __DIR__ . '/../vistas/admin_productos.php';
    }

    public function ventas() {
        $notaModel = new NotaVenta();
        $ventas = $notaModel->obtenerTodasConResumen();

        $detalles = [];
        foreach ($ventas as $venta) {
            $detalles[$venta['nro']] = $notaModel->obtenerDetallesPorNota((int)$venta['nro']);
        }

        $titulo = 'Administracion - Ventas';
        require_once __DIR__ . '/../vistas/admin_ventas.php';
    }
}
