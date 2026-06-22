<?php
define('DB_HOST','tu_host');
define('DB_PORT','tu_puerto');
define('DB_NAME','nombre_DB');
define('DB_USER','usuario');
define('DB_PASS','contraseña');
define('DB_CHARSET','utf8mb4');

class Database{
    private static $conexion = null;

    public static function getConexion(){
        if( self::$conexion === null){
            try{
                $dsn = 'mysql:host='. DB_HOST.
                ';port='. DB_PORT.
                ';dbname='. DB_NAME .
                ';charset=' . DB_CHARSET;

                self::$conexion = new PDO($dsn,DB_USER,DB_PASS ,[
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);

            }catch(PDOException $ex){
                die("Error de conexion de la base de datos" . $ex->getMessage());
            }
        }
        return self::$conexion;
    }
    private function __clone(){}
    private function __construct(){}

}

?>