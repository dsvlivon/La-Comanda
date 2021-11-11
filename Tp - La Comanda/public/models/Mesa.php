<?php
include_once "Empleado.php";

class Mesa{
    public $id;//AI
    public $codigo;//(STR) codigo propio 5 "CARACTERES"
    public $estado;//(STR) Enum de estados LIBRE/OCUPADA
    public $pedido;//(INT) id de obj(Pedido)
    public $mozo;//(INT) id de obj(Empleado)
    public $encuesta;//(INT) id de obj(encuesta)


    //region ABM
    public function crearMesa(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (codigo, estado, pedido, mozo, encuesta) 
                                                            VALUES (:codigo, :estado, :pedido, :mozo, :encuesta)");
        // $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        
        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':pedido', $this->pedido, PDO::PARAM_INT);
        $consulta->bindValue(':mozo', $this->mozo, PDO::PARAM_INT);
        $consulta->bindValue(':encuesta', $this->encuesta, PDO::PARAM_INT);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function obtenerUno($id){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public static function modificar($id){
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET 
        codigo = :codigo, estado = :estado, pedido = :pedido, mozo = :mozo, encuesta = :encuesta
        WHERE id = :id");
        
        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_INT); //x default
        $consulta->bindValue(':pedido', $this->pedido, PDO::PARAM_INT);
        $consulta->bindValue(':mozo', $this->mozo, PDO::PARAM_INT);
        $consulta->bindValue(':encuesta', $this->encuesta, PDO::PARAM_STR); //x dafault
        
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
        echo "Codigo: ".$this->codigo."\n";
        echo "Estado: ".$this->estado."\n";
        echo "Pedido: ".$this->pedido."\n";
        echo "Mozo: ".$this->mozo."\n";
        echo "Encuesta: ".$this->encuesta."\n";
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

    public static function validarMozo($mozo){
        echo "id de mozo: ".$mozo."\n"."\n";
        
        $lista = Empleado::obtenerTodos();
        // Empleado::Listar($lista);
        foreach ($lista as $e) {
            // var_dump($e);
            if($mozo == $e->id){
                // echo "match!";
                if($e->tipo == "Mozo" || $e->tipo == "Socio"){
                    // echo " habilitado!";
                    return TRUE;
                }
            }
        }
        echo "no te ubico maestro!";
        return FALSE;
    }

    static function generarCodigo() { 
        $c = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
        // echo $c;
        return  $c;
    } 
    //endregion
}