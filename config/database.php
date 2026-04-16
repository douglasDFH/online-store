<?php
class Database {
    private static $conexion = null;

    public static function conectar() {
        if (self::$conexion === null) {
            $host           = "localhost";
            $usuario        = "root";
            $password       = "";
            $base_de_datos  = "mydb";

            self::$conexion = new mysqli($host, $usuario, $password, $base_de_datos);

            if (self::$conexion->connect_error) {
                die("Error de conexión: " . self::$conexion->connect_error);
            }
            self::$conexion->set_charset("utf8");
        }
        return self::$conexion;
    }
}
