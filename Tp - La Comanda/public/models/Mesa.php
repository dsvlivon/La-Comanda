<?php
include_once "Empleado.php";

class Mesa{
    public $id;//AI
    public $estado;//(STR) Enum de estados LIBRE/OCUPADA
    public $mozo;//(INT) id de obj(Empleado)
   

    //region ABM
    public function crearMesa(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (estado, mozo) 
                                                            VALUES (:estado, :mozo)");
        // $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':mozo', $this->mozo, PDO::PARAM_INT);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function obtenerUno($id){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }

    public static function modificar($id){
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET 
        estado = :estado, mozo = :mozo WHERE id = :id");
        
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_INT);
        $consulta->bindValue(':mozo', $this->mozo, PDO::PARAM_INT);
        
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrar($id){
        $objAccesoDato = AccesoDatos::obtenerInstancia();
  
        $consulta = $objAccesoDato->prepararConsulta("DELETE mesas WHERE id = :id");
        $consulta->bindValue(':id', $producto, PDO::PARAM_INT);
        $consulta->execute();
    }
    //endregion

    //region Propias
    function Mostrar(){
        echo "Estado: ".$this->estado."\n";
        echo "Mozo: ".$this->mozo."\n";
        echo "-----------------------\n";
    }

    public static function Listar($lista){
        foreach ($lista as $obj) {
            echo "<ul>";
            echo "<li>"."Descripcion: ".$obj->codigo."</li>";
            echo "<li>"."Precio: ".$obj->pedido."</li>";
            // echo "<li>"."Tipo: ".$obj->encuesta."</li>";
            // echo "<li>"."Sector: ".$obj->sector."</li>";
            echo "</ul>";
        }
    }

    public static function validarMozo($id){
        echo "id ingresado: ".$id."\n"."\n";
        
        $lista = Empleado::obtenerTodos();
        foreach ($lista as $e) {
            // var_dump($e);
            if($id == $e->id){
                if($e->idTipo == 3 || $e->idTipo == 0){
                    return TRUE;
                }
            }
        }
        echo "Se requiere un Mozo Valido responsable!\n";
        return FALSE;
    } 
    //endregion
}