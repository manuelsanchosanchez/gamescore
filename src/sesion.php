<?php
/*
  clase encargada de gestionar
  las sesiones de la aplicacion
*/

//incluimos la clase para utilizar la base de datos y hacemos que esta la herede
include "bd.php";

//la clase sesion tendra todo lo necesario para gestionar las mismas
class Sesion extends BD
{
    //constantes para controlar el estado de la sesión
    const SESION_INICIADA = TRUE;
    const SESION_NO_INICIADA = FALSE;

    //estado de la sesión
    private $estadoSesion = self::SESION_NO_INICIADA;

    //instancia de la clase sesion
    private static $instanciaSesion;

    public function __construct()
    {
        parent::__construct();
    }

    // Si todavía no existe la sesión, la crea inicia la sesion y la devuelve     
    public static function getInstancia()
    {
        if (!isset(self::$instanciaSesion)) {
            self::$instanciaSesion = new self;
        }
        self::$instanciaSesion->iniSesion();
        return self::$instanciaSesion;
    }

    // Inicializa la sesion si no esta inicializada
    public function iniSesion()
    {
        if ($this->estadoSesion == self::SESION_NO_INICIADA) {
            $this->estadoSesion = session_start();
        }
        return $this->estadoSesion;
    }

    // Destruye la sesion
    public function destruirSesion()
    {

        if ($this->estadoSesion == self::SESION_INICIADA) {
            //session_destroy devuelve true si se ejecuta correctamente, 
            //al recoger el valor con ! nos dará un false si ha podido realizarlo que asignaremos al estado de la sesion            
            $this->estadoSesion = !session_destroy();
            unset($_SESSION);

            //volvemos a usar ! para devolver true en caso de que la sesion se encuentre como no iniciada
            return !$this->estadoSesion;
        }
        return false;
    }

    // con set podremos guardar cualquier valor en una variable en la sesion
    public function __set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    // con get obtendremos el valor de una variable de la sesion
    public function __get($name)
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }
    }

    // con isset sabremos si existe una variable de sesion
    public function __isset($name)
    {
        return isset($_SESSION[$name]);
    }


    // unset destruye una variable de sesion
    public function __unset($name)
    {
        unset($_SESSION[$name]);
    }
}