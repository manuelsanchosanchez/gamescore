<?php
/*
  clase encargada de gestionar
  los juegos de la aplicacion
*/

class Juegos extends BD
{
    public function __construct()
    {
        parent::__construct();
    }

    //metodo para buscar juegos con los filtros introducidos
    function leerJuegos($nombre = null, $plataforma = null, $genero = null, $anyo = null, $usuario = null)
    {
        if ($this->conectarBD())
        {
            //inicializamos las variables;
            $whereNombre = $whereGenero = $wherePlataforma = $whereAnyo = $whereUsuario = $innerUsuario="";

            //dependiendo de que filtros han pasado, añadiremos condiciones
            if($nombre)$whereNombre=" and nombre LIKE :nombre";
            if($plataforma)$wherePlataforma=" and idPlataforma = :idPlataforma";
            if($genero)$whereGenero=" and idGenero = :idGenero";
            if($anyo)$whereAnyo = " and anyoLanzamiento= :anyoLanzamiento";
            if($usuario)$whereUsuario = "and usuario = :usuario";

            //consulta principal con varios inner join y un left join
            $query = "select fichaJuego.id as id,nombre,plataformas.id as idPlataforma,plataforma,generos.id as idGenero,genero,anyoLanzamiento,fichaJuego.nota,usuario,notaUsuario 
            FROM fichaJuego
            inner join plataformas on idPlataforma=plataformas.id
            inner join generos on idGenero=generos.id
            inner join juegos on idJuego=juegos.id
            left join usuarioJuego on idFichaJuego = fichaJuego.id
            where 1=1 $whereNombre $wherePlataforma $whereGenero $whereAnyo $whereUsuario
            ORDER BY nombre";

            $pQuery = $this->conn->prepare($query);
            if($nombre){$nombre = "%".$nombre."%";$pQuery->bindParam(':nombre', $nombre);}
            if($plataforma)$pQuery->bindParam(':idPlataforma', $plataforma);
            if($genero)$pQuery->bindParam(':idGenero', $genero);
            if($anyo)$pQuery->bindParam(':anyoLanzamiento', $anyo);
            if($usuario)$pQuery->bindParam(':usuario', $usuario);
            $pQuery->execute();
            
            return $pQuery;
        }
    }

    //metodo para eliminar un juego de la base de datos
    function eliminarJuego($id)
    {
        if ($this->conectarBD())
        {
            $query = "DELETE FROM fichaJuego WHERE id = :id";
            
            $pQuery = $this->conn->prepare($query);
            $pQuery->bindParam(':id', $id);
            $pQuery->execute();
            
            if($pQuery->rowCount() == 1)return true;
            else return false;
        }
    }

    //metodo para insertar un juego a la base de datos
    public function insertJuego($nombre, $plataforma, $genero, $anyo, $nota)
    {
        //revisamos que los datos esten correctos
        if($nombre == "" || $plataforma == "" || $genero == "" || $anyo == "" || $nota == "")return ERROR_FALTAN_DATOS;
        if(strlen($nombre)<3)return ERROR_LONGITUD;
        else
        {
            if ($this->conectarBD()) 
            {
                //utilizamos upper para que no importe si el titulo introducido esta en mayusculas o minusculas
                $query = "select * from juegos where upper(nombre)=upper(:nombre);";
                $pQuery = $this->conn->prepare($query);
                $pQuery->bindParam(':nombre', $nombre);
                $pQuery->execute();
                $count = $pQuery->rowCount();

                //si no hay registro de este juego, lo creamos
                if ($count == 0)
                {
                    $query = "insert into juegos(nombre) values (upper(:nombre))";
                    $pQuery = $this->conn->prepare($query);
                    $pQuery->bindParam(':nombre', $nombre);

                    $pQuery->execute();
                    if($pQuery->rowCount() > 0)
                    {
                        //guardamos el id creado para insertar la ficha
                        $idJuego = $this->conn->lastInsertId();
                    }
                    else
                    {
                        return ERROR_INSERTA_REGISTRO; 
                    }
                }
                elseif($count == 1)
                {
                    //cogemos el id del juego para insertar la ficha
                    $pQuery->setFetchMode(PDO::FETCH_ASSOC);
                    $datosJuego = $pQuery->fetch();
                    $idJuego = $datosJuego['id'];                    
                }
                else return REGISTRO_EXISTE;

                //insertamos el juego en la ficha con los valores proporcionados
                $query = "insert into fichaJuego(idJuego,idPlataforma,idGenero,anyoLanzamiento,nota) values (:idJuego,:plataforma,:genero,:anyo,:nota)";
                $pQuery = $this->conn->prepare($query);
                $pQuery->bindParam(':idJuego', $idJuego);
                $pQuery->bindParam(':plataforma', $plataforma);
                $pQuery->bindParam(':genero', $genero);
                $pQuery->bindParam(':anyo', $anyo);
                $pQuery->bindParam(':nota', $nota);

                $pQuery->execute();
                if($pQuery->rowCount() > 0)
                {
                    return REGISTRO_INSERTADO;
                }
                else
                {
                    return ERROR_INSERTA_REGISTRO; 
                }
            }
        }
    }

    //metodo para marcar/desmarcar un juego como favorito
    public function favJuego($id, $usuario)
    {
        if ($this->conectarBD()) 
        {
            //buscamos si el usuario ya lo ha marcado
            $query = "select * from usuarioJuego where idFichaJuego = :id and usuario = :usuario;";
            $pQuery = $this->conn->prepare($query);
            $pQuery->bindParam(':id', $id);
            $pQuery->bindParam(':usuario', $usuario);
            $pQuery->execute();
            $count = $pQuery->rowCount();

            //si no hay registro de este juego es que no lo tiene como favorito, lo creamos
            if ($count == 0)
            {
                $query = "insert into usuarioJuego(idFichaJuego,usuario) values (:id,:usuario)";
                $pQuery = $this->conn->prepare($query);
                $pQuery->bindParam(':id', $id);
                $pQuery->bindParam(':usuario', $usuario);

                $pQuery->execute();
                if($pQuery->rowCount() > 0)
                {
                    return REGISTRO_INSERTADO; 
                }
                else
                {
                    return ERROR_INSERTA_REGISTRO; 
                }
            }
            elseif($count == 1)
            {
                // si ya existe el registro, lo eliminamos
                $query = "delete from usuarioJuego where idFichaJuego = :id and usuario = :usuario";
                $pQuery = $this->conn->prepare($query);
                $pQuery->bindParam(':id', $id);
                $pQuery->bindParam(':usuario', $usuario);

                $pQuery->execute();
                if($pQuery->rowCount() > 0)
                {
                    return REGISTRO_ELIMINADO; 
                }
                else
                {
                    return ERROR_ELIMINAR_REGISTRO; 
                }                   
            }
            else return ERROR_ELIMINAR_REGISTRO;
        }
    }

    //metodo para actualizar los datos de un juego
    public function actJuego($id, $nombre, $plataforma, $genero, $anyo, $nota)
    {
        if($nombre == "" || $plataforma == "" || $genero == "" || $anyo == "" || $nota == "")return ERROR_FALTAN_DATOS;
        if(strlen($nombre)<3)return ERROR_LONGITUD;
        else
        {
            if ($this->conectarBD()) 
            {
                $query = "select * from juegos where upper(nombre)=upper(:nombre);";
                $pQuery = $this->conn->prepare($query);
                $pQuery->bindParam(':nombre', $nombre);
                $pQuery->execute();
                $count = $pQuery->rowCount();

                //si no hay registro de este nuevo nombre del juego, lo creamos
                if ($count == 0)
                {
                    $query = "insert into juegos(nombre) values (upper(:nombre))";
                    $pQuery = $this->conn->prepare($query);
                    $pQuery->bindParam(':nombre', $nombre);

                    $pQuery->execute();
                    if($pQuery->rowCount() > 0)
                    {
                        //guardamos el id creado para insertar la ficha
                        $idJuego = $this->conn->lastInsertId();
                    }
                    else
                    {
                        return ERROR_INSERTA_REGISTRO; 
                    }

                    /*
                      PARA MEJORAR ESTA CLASE, DEBERÍAMOS GESTIONAR QUE PASA CON LOS JUEGOS 
                      QUE SE QUEDAN'HUÉRFANOS' AL MODIFICAR EL NOMBRE DE UN JUEGO Y INSERTAR
                      UN REGISTRO NUEVO.
                    */
                }
                elseif($count == 1)
                {
                    //cogemos el id del juego para insertar la ficha
                    $pQuery->setFetchMode(PDO::FETCH_ASSOC);
                    $datosJuego = $pQuery->fetch();
                    $idJuego = $datosJuego['id'];                    
                }
                else return REGISTRO_EXISTE;

                //actualizamos los valores del juego
                $query = "update fichaJuego set idJuego = :idJuego, idPlataforma = :plataforma, idGenero = :genero, anyoLanzamiento = :anyo, nota = :nota where id = :id";
                
                $pQuery = $this->conn->prepare($query);
                $pQuery->bindParam(':id', $id);
                $pQuery->bindParam(':idJuego', $idJuego);
                $pQuery->bindParam(':plataforma', $plataforma);
                $pQuery->bindParam(':genero', $genero);
                $pQuery->bindParam(':anyo', $anyo);
                $pQuery->bindParam(':nota', $nota);

                $pQuery->execute();
                if($pQuery->rowCount() == 1)
                {
                    return REGISTRO_ACTUALIZADO;
                }
                else
                {
                    return ERROR_ACTUALIZA_REGISTRO; 
                }
            }
        }
    }

    //metodo para listar las plataformas
    function leerPlataformas()
    {
        if ($this->conectarBD())
        { 
            $query = "select id,plataforma FROM plataformas order by plataforma";
            
            $pQuery = $this->conn->prepare($query);
            $pQuery->execute();
            
            return $pQuery;
        }
    }

    //metodo para eliminar una plataforma de la bd
    function eliminarPlataforma($id)
    {
        if ($this->conectarBD())
        {
            $query = "DELETE FROM plataformas WHERE id = :id";
            
            $pQuery = $this->conn->prepare($query);
            $pQuery->bindParam(':id', $id);
            $pQuery->execute();
            
            if($pQuery->rowCount() == 1)return true;
            else return false;
        }
    }

    //metodo para insertar una plataforma en la bd
    public function insertPlataforma($nombre)
    {
        if(strlen($nombre)<3)return ERROR_LONGITUD;
        else
        {
            if ($this->conectarBD()) 
            {
                $query = "select * from plataformas where upper(plataforma)=upper(:plataforma);";
                $pQuery = $this->conn->prepare($query);
                $pQuery->bindParam(':plataforma', $nombre);
                $pQuery->execute();
                $count = $pQuery->rowCount();

                if ($count == 0)
                {
                    $query = "insert into plataformas(plataforma) values (upper(:plataforma))";
                    $pQuery = $this->conn->prepare($query);
                    $pQuery->bindParam(':plataforma', $nombre);
                    
                    $pQuery->execute();
                    if($pQuery->rowCount() > 0)
                    {
                        return REGISTRO_INSERTADO;
                    }
                    else
                    {
                        return ERROR_INSERTA_REGISTRO; 
                    }
                }
                else return REGISTRO_EXISTE;
            }
        }
    }

    //metodo para actualizar una plataforma en la bd
    public function actPlataforma($id,$nombre)
    {
        if(strlen($nombre)<3)return ERROR_LONGITUD;
        else
        {
            if ($this->conectarBD()) 
            {
                $query = "select * from plataformas where upper(plataforma)=upper(:plataforma);";
                $pQuery = $this->conn->prepare($query);
                $pQuery->bindParam(':plataforma', $nombre);
                $pQuery->execute();
                $count = $pQuery->rowCount();

                if ($count == 0)
                {
                    $query = "update plataformas set plataforma = upper(:plataforma) WHERE id = :id;";

                    $pQuery = $this->conn->prepare($query);
                    $pQuery->bindParam(':plataforma', $nombre);
                    $pQuery->bindParam(':id', $id);
                    
                    $pQuery->execute();
                    if($pQuery->rowCount() > 0)
                    {
                        return REGISTRO_ACTUALIZADO;
                    }
                    else
                    {
                        return ERROR_ACTUALIZA_REGISTRO; 
                    }
                }
                else return REGISTRO_EXISTE;
            }
        }
    }

    //metodo para listar los generos
    function leerGeneros()
    {
        if ($this->conectarBD())
        {
            $query = "select id,genero FROM generos order by genero";
            
            $pQuery = $this->conn->prepare($query);
            $pQuery->execute();
            
            return $pQuery;
        }
    }

    //metodo para eliminar una plataforma de la bd
    function eliminarGenero($id)
    {
        if ($this->conectarBD())
        {
            $query = "DELETE FROM generos WHERE id = :id";
            
            $pQuery = $this->conn->prepare($query);
            $pQuery->bindParam(':id', $id);
            $pQuery->execute();
            
            if($pQuery->rowCount() == 1)return true;
            else return false;
        }
    }

    //metodo para insertar un genero a la bd
    public function insertGenero($nombre)
    {
        if(strlen($nombre)<2)return ERROR_LONGITUD;
        else
        {
            if ($this->conectarBD()) 
            {
                $query = "select * from generos where upper(genero)=upper(:genero);";
                $pQuery = $this->conn->prepare($query);
                $pQuery->bindParam(':genero', $nombre);
                $pQuery->execute();
                $count = $pQuery->rowCount();

                if ($count == 0)
                {
                    $query = "insert into generos(genero) values (upper(:genero))";
                    $pQuery = $this->conn->prepare($query);
                    $pQuery->bindParam(':genero', $nombre);
                    
                    $pQuery->execute();
                    if($pQuery->rowCount() > 0)
                    {
                        return REGISTRO_INSERTADO;
                    }
                    else
                    {
                        return ERROR_INSERTA_REGISTRO; 
                    }
                }
                else return REGISTRO_EXISTE;
            }
        }
    }

    //metodo para actualizar un genero en la bd
    public function actGenero($id,$nombre)
    {
        if(strlen($nombre)<3)return ERROR_LONGITUD;
        else
        {
            if ($this->conectarBD()) 
            {
                $query = "select * from generos where upper(genero)=upper(:genero);";
                $pQuery = $this->conn->prepare($query);
                $pQuery->bindParam(':genero', $nombre);
                $pQuery->execute();
                $count = $pQuery->rowCount();

                if ($count == 0)
                {
                    $query = "update generos set genero = upper(:genero) WHERE id = :id;";
                    $pQuery = $this->conn->prepare($query);
                    $pQuery->bindParam(':genero', $nombre);
                    $pQuery->bindParam(':id', $id);
                    
                    $pQuery->execute();
                    if($pQuery->rowCount() > 0)
                    {
                        return REGISTRO_ACTUALIZADO;
                    }
                    else
                    {
                        return ERROR_ACTUALIZA_REGISTRO; 
                    }
                }
                else return REGISTRO_EXISTE;
            }
        }
    }
}