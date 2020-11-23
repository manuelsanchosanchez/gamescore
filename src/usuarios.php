<?php
/*
  clase encargada de gestionar
  los usuarios de la aplicacion
*/

//incluimos la clase para gestionar sesiones y hacemos que esta clase la herede
include "sesion.php";
class Usuarios extends Sesion
{
    public function __construct()
    {
        parent::__construct();
        $this->sesion = null;
    }

    //metodo para logarse
    public function login($usuario, $password)
    {
        $oUsuario = null;
        if($usuario == "" || $password == "")
        {
            return USUARIO_BLANCO;
        }
        elseif ($this->conectarBD())
        {            
            $query = "select usuario, email, nivel,DATE_FORMAT(alta,'%d-%m-%Y %H::%i:%s')as altaEs from usuarios where usuario= :usuario and password= :password and estado = 1;";
            //con prepare prevenimos inyecciones SQL
            $pQuery = $this->conn->prepare($query);

            //y con bindParam vinculamos las variables y evitamos el uso de comillas en las sentencias
            $pQuery->bindParam(':usuario', $usuario);
            $pQuery->bindParam(':password', $password);

            //ejecutamos la query
            $pQuery->execute();
            
            //recogemos los datos con el nombre de la columna mediante FETCH_ASSOC
            $pQuery->setFetchMode(PDO::FETCH_ASSOC);
            while ($usuario = $pQuery->fetch())
            {
                //creamos variable tipo Usuario y asignamos los valores de la bd
                $oUsuario = new Usuario(); 
                $oUsuario->usuario = $usuario['usuario'];
                $oUsuario->email = $usuario['email'];
                $oUsuario->nivel = $usuario['nivel'];
                $oUsuario->alta = $usuario['altaEs'];
            }

            //creamos la sesión y la guardamos
            $this->sesion = $this->getInstancia();

            if ($oUsuario != null)
            {
                //si el login se ha realizado correctamente guardamos en la sesion los datos de usuario
                $datosUsuario = json_encode($oUsuario);
                $this->sesion->__set("sesionUsuario",  $datosUsuario);
                return $oUsuario->nivel;
            }
            else
            {
                //si no se ha realizado el login correctamente reseteamos los datos de usuario
                $this->sesion->__set("sesionUsuario",  null);
                return USUARIO_NO_ENCONTRADO;
            }
        }
    }

    //metodo para listar usuarios
    function leerUsuarios($id = null)
    {
        //tratamos de conectar
        if ($this->conectarBD())
        {
            // si se ha pasado un id se realiza la query solo de ese id de usuario, si no de todos ellos
            if(isset($id)) $query = "select id, usuario, email, date_format(alta,'%d-%m-%Y') as altaes, nivel, estado FROM usuarios where id = :id order by usuario";
            else $query = "select id, usuario, email, date_format(alta,'%d-%m-%Y') as altaes, nivel, estado FROM usuarios order by usuario";
            
            $pQuery = $this->conn->prepare($query);
            if(isset($id))$pQuery->bindParam(':id', $id);
            $pQuery->execute();
            
            //devolvemos el objecto pdo para poder trabajar con el
            return $pQuery;
        }
    }

    //metodo que crea un usuario nuevo
    public function nuevoUsuario($usuario, $password, $passwordc, $email, $nivel)
    {
        //revisamos los datos y devolvemos el error si es el caso
        if(strlen($usuario)<3)return USUARIO_CORTO;
        elseif(strlen($password)<4)return PASSWORD_CORTA;
        elseif($password!=$passwordc)return PASSWORD_NO_COINCIDE;
        elseif(!filter_var(htmlspecialchars_decode($email, ENT_QUOTES), FILTER_VALIDATE_EMAIL))return EMAIL_NO_VALIDO;
        else
        {
            //usamos sha1 y md5 para la encriptar la contraseña
            $lPassC = sha1(md5("gamescore" . $password));

            //bucamos si ya existe un usuario con ese nombre
            $query = "select * from usuarios where (usuario=:usuario) and estado = 1;";

            if ($this->conectarBD())
            {
                //con prepare prevenimos inyecciones SQL
                $pQuery = $this->conn->prepare($query);

                //y con bindParam vinculamos las variables y evitamos el uso de comillas en las sentencias
                $pQuery->bindParam(':usuario', $usuario);
                
                //ejecutamos la query
                $pQuery->execute();
                $count = $pQuery->rowCount();

                //si encuentra uno, devuelve error
                if ($count > 0)
                {
                    return USUARIO_YA_EXISTE;
                }
                else
                {
                    //insertamos el nuevo usuario en la bd
                    $query = "insert into usuarios(usuario, password, email, nivel, estado) values (:usuario,:password,:email,:nivel,1)";
                    $pQuery = $this->conn->prepare($query);
                    $pQuery->bindParam(':usuario', $usuario);
                    $pQuery->bindParam(':password', $lPassC);
                    $pQuery->bindParam(':email', $email);
                    $pQuery->bindParam(':nivel', $nivel);

                    $pQuery->execute();

                    if($pQuery->rowCount() > 0)
                    {
                        return USUARIO_CREADO; 
                    }
                    else
                    {
                        return false;
                    }
                    
                }
            }
        }
    }

    //metodo para eliminar un usuario de la bd
    function eliminarUsuario($id)
    {
        if ($this->conectarBD())
        {
            $query = "DELETE FROM usuarios WHERE id = :id";
            
            $pQuery = $this->conn->prepare($query);
            $pQuery->bindParam(':id', $id);
            $pQuery->execute();
            
            if($pQuery->rowCount() == 1)return true;
            else return false;
        }
    }

    //metodo para actualizar un usuario
    function actUsuario($id, $usuario, $password, $passwordc, $email, $nivel, $estado)
    {
        if(strlen($usuario)<3)return USUARIO_CORTO;
        elseif(isset($password) && $password!="" && strlen($password)<4)return PASSWORD_CORTA;
        elseif($password!=$passwordc)return PASSWORD_NO_COINCIDE;
        elseif(!filter_var(htmlspecialchars_decode($email, ENT_QUOTES), FILTER_VALIDATE_EMAIL))return EMAIL_NO_VALIDO;
        else
        {
            if($password!="")$lPassC = sha1(md5("gamescore" . $password));

            //buscamos si el nombre de usuario nuevo coincide con otro usuario
            $query = "select * from usuarios where usuario = :usuario and id <> :id and estado = 1 ;";

            if ($this->conectarBD())
            {
                $pQuery = $this->conn->prepare($query);
                $pQuery->bindParam(':usuario', $usuario);
                $pQuery->bindParam(':id', $id);
                $pQuery->execute();
                $count = $pQuery->rowCount();
                
                //si ya existe lanzamos error
                if ($count > 0)
                {
                    return USUARIO_YA_EXISTE;
                }
                else
                {
                    //si no se ha pasado password, no los actualiza
                    if(isset($lPassC))$query = "update usuarios set usuario = :usuario, password = :password, email = :email, nivel = :nivel, estado = :estado where id = :id";
                    else $query = "update usuarios set usuario = :usuario, email = :email, nivel = :nivel, estado = :estado where id = :id";                    

                    $pQuery = $this->conn->prepare($query);
                    $pQuery->bindParam(':id', $id);
                    $pQuery->bindParam(':usuario', $usuario);
                    if(isset($lPassC))$pQuery->bindParam(':password', $lPassC);
                    $pQuery->bindParam(':email', $email);
                    $pQuery->bindParam(':nivel', $nivel);
                    $pQuery->bindParam(':estado', $estado);

                    $pQuery->execute();

                    if($pQuery->rowCount() > 0)
                    {
                        return USUARIO_ACTUALIZADO; 
                    }
                    else
                    {
                        return ERROR_ACTUALIZAR_USUARIO;
                    }
                    
                }
            }
        }
    }
}

class Usuario
{
    public $usuario = "";
    public $email = "";
    public $nivel = "";
    public $alta = "";
}