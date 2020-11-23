<?php
//incluimos el fichero de configuracion
include '../config/config.php';

//definimos la clase para conectar con nuestra base de datos
class BD
{
    //variable publica que guardara la conexion
    public $conn = null;

    //en el constructor de la clase asignamos los datos de la conexion
    public function __construct()
    {
        $this->usuariodb = DB_USUARIO;
        $this->passworddb = DB_PASSWORD;
        $this->servidordb = DB_SERVIDOR;
        $this->databasedb = DB_DATABASE;
    }

    //funcion para realizar la conexión
    public function conectarBD()
    {        
        try 
        {
            $this->conn = new PDO("mysql:host=" . $this->servidordb . ";dbname=" . $this->databasedb, $this->usuariodb, $this->passworddb);
            return true;
        }
        catch (PDOException $e)
        {
            echo "Ocurrió un error al conectar a la bbdd: " . $e->getMessage() . "\n";
            return false;
        }
    }
}
?>