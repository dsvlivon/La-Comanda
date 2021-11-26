<?php

class Empleado {
    public $id;
    public $nombre;//(STR) 
    public $clave;//(STR) codigo hash
   
    public $tipo;
    public $sector; 
    public $idTipo;
    public $idSector; 

    //region ABM 
    public function crearEmpleado(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO empleados (nombre, clave, sector, idSector, tipo, idTipo) 
        VALUES (:nombre, :clave, :sector, :idSector, :tipo, :idTipo)");
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->bindValue(':sector', $this->sector,PDO::PARAM_STR);
        $consulta->bindValue(':idSector', $this->idSector,PDO::PARAM_INT);
        $consulta->bindValue(':tipo', $this->tipo,PDO::PARAM_STR);
        $consulta->bindValue(':idTipo', $this->idTipo, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM empleados");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Empleado');
    }
    
    public static function obtenerTipo($tipo){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM empleados WHERE tipo = :tipo");
        $consulta->bindValue('tipo', $tipo, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Empleado');
    }

    public static function obtenerUno($id){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM empleados WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Empleado');
    }

    public static function modificar($id){
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE empleados SET nombre = :nombre, clave = :clave, tipo = :tipo WHERE id = :id");
        
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_INT);
        $cunsulta->bindValue(':id', $id, PDO::PARAM_INT);
        // $consulta->bindValue(':sector', $this->sector);

        $consulta->execute();
    }

    public static function borrar($id){
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE empleados WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }
    //endregion

    //region Propias
    public function Mostrar(){
        echo "Id: ".$this->id."\n";
        echo "Nombre: ".$this->nombre."\n";
        echo "Tipo: ".$this->tipo."\n";
        echo "Sector: ".$this->sector."\n";
        echo "-----------------------\n";
    }

    public static function Listar($lista){
        foreach ($lista as $obj) {
            $obj->Mostrar();
        }
    }

    public static function validarIdSector($x){
        switch ($x) {
            case "Todos":
                return 0;
                break;
            case "Cervezas":
                return 1;
                break;
            case "Tragos":
                return 2;
                break;
            case "CandyBar":
                return 3;
                break;
            case "Cocina":
                return 4;
                break;
            case "Mesas":
              return 5;
              break;
  
            default:
                return 9;//unType
                break;
        }
    }

    public static function validarIdTipo($x){
        switch ($x) {
            case "Socio":
                return 0;
                break;
            case "Cocinero":
                return 1;
                break;
            case "Bartender":
                return 2;
                break;
            case "Mozo":
                return 3;
                break;
  
            default:
                return 9;//unType
                break;
        }
    }
    
    public function ValidarUsuario($e){
        $lista = Empleado::obtenerTodos();
        foreach ($lista as $emp) {
            if($emp->id == $e->id){
                // var_dump($e);
                if (password_verify($e->clave, $emp->clave)) {
                    $e->nombre = $emp->nombre;
                    $e->idTipo = $emp->idTipo;
                    $e->tipo = $emp->tipo;
                    $e->idSector = $emp->idSector;
                    $e->sector = $emp->sector;
                    echo "Bienvenido!\n id: ". $e->id." - nombre: ".$e->nombre."/ tipo :".$e->tipo."\n";
                    // echo "RECONOZCO AL USER";
                    return TRUE;
                }        
            }
        }
        return FALSE;
    }
    //endregion
}