<?php

include_once "Producto.php";
include_once "Mesa.php";


class Pedido {
    public $numero;//(INT) identificador propio
    public $items;//(array)lista de items
    public $estado;//(bool)if(foreach(items))== ok => estado == ok
    public $mesa;//(STR) codigo de obj(mesa)
    public $mozo;//(INT) id de obj(empleado)
    public $demora;//(INT) value mins
    public $monto;//(INT) value ar$
    public $fecha;
    public $foto;


    //region ABM
    public function crearPedido(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (items, estado, mesa, mozo, demora, monto, fecha, foto)
        VALUES (:items, :estado,:mesa, :mozo, :demora, :monto, :fecha, :foto)");
        $consulta->bindValue(':items', $this->items, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':mesa', $this->mesa, PDO::PARAM_STR);
        $consulta->bindValue(':mozo', $this->mozo, PDO::PARAM_INT);
        $consulta->bindValue(':demora', $this->demora, PDO::PARAM_INT);
        $consulta->bindValue(':monto', $this->monto, PDO::PARAM_INT);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerUno($num){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE num = :num");
        $consulta->bindValue(':num', $num, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Item');
    }

    public static function modificar($num){
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos 
        SET items = :items, estado = :estado, mesa = :mesa, mozo = :mozo WHERE numero = :numero");
        $consulta->bindValue(':items', $this->items, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':mesa', $this->mesa, PDO::PARAM_INT);
        $consulta->bindValue(':mozo', $this->mozo, PDO::PARAM_INT);
        $consulta->bindValue(':demora', $this->demora, PDO::PARAM_INT);
        $consulta->bindValue(':monto', $this->monto, PDO::PARAM_INT);

        $consulta->bindValue(':numero', $num, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrar($num){
        $objAccesoDato = AccesoDatos::obtenerInstancia();
  
        $consulta = $objAccesoDato->prepararConsulta("DELETE pedidos WHERE num = :num");
        $consulta->bindValue(':numero', $num, PDO::PARAM_INT);
        $consulta->execute();
    }
    //endregion

    //Propias
    function Mostrar(){
        echo "Productos: ".Producto::Listar($this->items)."\n";
        echo "Estado: ".$this->estado."\n";
        echo "N° de Mesa: ".$this->mesa."\n";
        echo "Mozo: ".$this->mozo."\n";
        echo "Demora aprox.: ".$this->demora."\n";
        echo "Monto actual: ".$this->monto."\n";
        echo "-----------------------\n";
    }

    public static function Listar($lista){
        foreach ($lista as $obj) {
            // echo "<ul>";
            // echo "<li>"."Descripcion: ".$obj->codigo."</li>";
            // echo "<li>"."Precio: ".$obj->pedido."</li>";
            // echo "<li>"."Tipo: ".$obj->encuesta."</li>";
            // echo "<li>"."Sector: ".$obj->sector."</li>";
            // echo "</ul>";
            $obj->Mostar();
        }
    }

    public static function validarStock($lista, $p, $archivo){
        $foto = $_FILES["foto"]; 
        $status = FALSE;       
        if($lista != NULL && $p != NULL){
            foreach ($lista as $obj) {
                if($p->Equals($obj)) {
                    $msg = "Pizaa Existente!"."</br>"."Stock anterior: ".$obj->cantidad."</br>"."Nuevo Stock: ".$p->cantidad+$obj->cantidad;
                    $obj->cantidad += $p->cantidad;
                    $msg = $msg."</br>Precio anterior: ".$obj->precio."</br>"."Precio nuevo : ".$p->precio;
                    $obj->precio = $p->precio+0;
                    echo "ACTUALIZANDO: </br>";
                    // var_dump($obj);
                    $status = FALSE;
                    break;
                } else {
                    $msg = "Producto Inexistente!"."</br>"."Se ingresa nuevo producto!";
                    $status = TRUE;
                }
            }
            if($status) {
                array_push($lista, $p);
                $p->GuardarPic($foto);
            }
            Archivo::GuardarJson($lista, $archivo);

            echo $msg;
        }
    }

    public function GuardarPic($foto){
        $dir_subida = 'ImagenesDeMesas/';
        if (!file_exists($dir_subida)) {
            mkdir('ImagenesDeMesas/', 0777, true);    
        }
        $extension = explode(".", $foto["name"]);
        
        $destino = $dir_subida."Mesa - ".$this->mesa."_ Mozo - ".$this->mozo.".".end($extension);
    
        if(move_uploaded_file($foto["tmp_name"],$destino)){
            echo "\nArchivo movido con exito en destino! ";
            $this->foto = $destino;
        } else {
            echo "Error";
            var_dump($foto["error"]);
        }
    }

    public static function calcularMonto($lista){
        $monto = 0;
        foreach ($lista as $obj) {
            $monto = $obj->precio + $monto;
        }
        $monto = $monto * 1.21;
        return $monto;
    }

    public static function calcularDemora($lista){
        $monto = 0;
        foreach ($lista as $obj) {
            $monto = $obj->tiempo + $monto;
        }
        // $monto = $monto * 1.21;
        return $monto;
    }
    //endregion
}