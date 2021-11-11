<?php
include_once "Empleado.php";
include_once "Mesa.php";


class Sector{ //DEPRECATED
    public $id;//AI
    public $descripcion;//(STR) txt descrp
    public $empleados; //(Array) lista de empleadoss
    // public $stock; // no se si corresponde... 
    
    public function crearSector(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO sectores (descripcion, empleados, mesas) 
        VALUES (:descripcion, :empleados, :mesas)");
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':empleados', $this->empleados, PDO::PARAM_STR);
        $consulta->bindValue(':mesas', $this->mesas, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM sectores");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Sector');
    }

    public static function obtenerUno($id){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM sectores WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }

    public static function modificar($id){
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE sectores 
        SET descripcion = :descripcion, empleados = :empleados, mesas = :mesas
        WHERE id = :id");
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':empleados', $this->empleados, PDO::PARAM_STR);
        $consulta->bindValue(':mesas', $this->mesas, PDO::PARAM_STR);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);

        $consulta->execute();
    }

    public static function borrar($id){
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE sectores WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }
}