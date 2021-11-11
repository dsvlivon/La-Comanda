<?php

class Empleado {
    public $id;
    public $nombre;//(STR) 
    public $clave;//(STR) codigo hash
    public $tipo;//(STR) clasif del empleado
    public $sector;//(INT) id del sector 

    //region ABM 
    public function crearEmpleado(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO empleados (nombre, clave, tipo, sector) VALUES (:nombre, :clave, :tipo, :sector)");
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $this->sector,PDO::PARAM_INT);
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
    function Mostrar(){
        echo "Id: ".$this->id."</br>";
        echo "Nombre: ".$this->nombre."</br>";
        echo "Tipo: ".$this->tipo."</br>";
        echo "Sector: ".$this->sector."</br>";
        echo "-----------------------</br>";
    }

    public static function Listar($lista){
        foreach ($lista as $obj) {
            // echo "<ul>";
            // echo "<li>"."Id: ".$obj->id."</li>";
            // echo "<li>"."Nombre: ".$obj->nombre."</li>";
            // echo "<li>"."Tipo: ".$obj->tipo."</li>";
            // echo "<li>"."Sector: ".$obj->sector."</li>";
            // echo "</ul>";
            $obj->Mostrar();
        }
    }
    
    public function ValidarEmpleado($e){
        $lista = Empleado::obtenerTodos();
        foreach ($lista as $emp) {
            if($emp->id == $e->id){
                // var_dump($e);
                if (password_verify($e->clave, $emp->clave)) {
                    $e->tipo = $emp->tipo;
                    $e->sector = $emp->sector;
                    $e->nombre = $emp->nombre;
                    echo "Bienvenido!\n nombre: ".$e->nombre."/ tipo :".$e->tipo."\n";
                    // echo "RECONOZCO AL USER";
                    return TRUE;
                }        
            }
        }
        return FALSE;
    }
    //endregion
}