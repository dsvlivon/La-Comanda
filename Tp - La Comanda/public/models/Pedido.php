<?php

include_once "Producto.php";
include_once "Mesa.php";


class Pedido {
    public $numero;//(INT) identificador propio
    public $codigo;
    public $producto;
    public $cantidad;
    public $mesa;//(STR) codigo de obj(mesa)
    public $mozo;//(INT) id de obj(empleado)
    public $demora;//(INT) value mins
    public $fecha;
    public $foto;
    public $idEstado;
    public $estado;    

    //region ABM
    public function crearPedido(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (codigo, producto, idSector, cantidad, mesa, mozo, demora, fecha, foto, estado, idEstado)
        VALUES (:codigo, :producto, :idSector, :cantidad, :mesa, :mozo, :demora, :fecha, :foto, :estado, idEstado)");
        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_STR);
        $consulta->bindValue(':producto', $this->producto, PDO::PARAM_INT);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':mesa', $this->mesa, PDO::PARAM_INT);
        $consulta->bindValue(':mozo', $this->mozo, PDO::PARAM_INT);
        $consulta->bindValue(':demora', $this->demora, PDO::PARAM_INT);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':idEstado', $this->idEstado, PDO::PARAM_INT);        
        $consulta->bindValue(':idSector', $this->idSector, PDO::PARAM_INT);
        
        $consulta->execute();
        
        return $objAccesoDatos->obtenerUltimoId();
    }

    public function modificar(){
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos 
        SET producto = :producto, cantidad = :cantidad, mesa = :mesa, mozo = :mozo, demora = :demora, fecha = :fecha, foto = :foto, estado = :estado, idEstado = :idEstado
        WHERE numero = :numero");
 
        //  $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_STR);
        $consulta->bindValue(':producto', $this->producto, PDO::PARAM_STR);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':mesa', $this->mesa, PDO::PARAM_STR);
        $consulta->bindValue(':mozo', $this->mozo, PDO::PARAM_INT);
        $consulta->bindValue(':demora', $this->demora, PDO::PARAM_INT);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':idEstado', $this->idEstado, PDO::PARAM_INT);

        $consulta->bindValue(':numero', $this->numero, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrar($num){
        $objAccesoDato = AccesoDatos::obtenerInstancia();
  
        $consulta = $objAccesoDato->prepararConsulta("DELETE pedidos WHERE num = :num");
        $consulta->bindValue(':numero', $num, PDO::PARAM_INT);
        $consulta->execute();
    }
    //endregion

    //region Consultas
    public static function obtenerTodos(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerUno($id){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function obtenerPorCodigo($codigo){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = "SELECT * FROM pedidos WHERE pedidos.codigo = :codigo";
        $consulta = $objAccesoDatos->prepararConsulta($query);
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerUnoPorCodigo($codigo){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = "SELECT * FROM pedidos WHERE pedidos.codigo = :codigo";
        $consulta = $objAccesoDatos->prepararConsulta($query);
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function obtenerPorEstado($estado){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = "SELECT * FROM pedidos WHERE pedidos.idEstado = :estado GROUP BY pedidos.codigo";
        $consulta = $objAccesoDatos->prepararConsulta($query);
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPendientesCocina(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = "SELECT * FROM pedidos WHERE pedidos.idSector = 3 OR pedidos.idSector = 4 AND pedidos.estado = 'Pendiente'";
        $consulta = $objAccesoDatos->prepararConsulta($query);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPendientesBartender(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = "SELECT * FROM pedidos WHERE pedidos.idSector = 1 OR pedidos.idSector = 2 AND pedidos.estado = 'Pendiente'";
        $consulta = $objAccesoDatos->prepararConsulta($query);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    //endregion

    //Propias
    function mostrarPedido(){
        echo "Codigo: ".$this->codigo."\n";
        echo "Estado: ".$this->estado."\n";
        echo "N° de Mesa: ".$this->mesa."\n";
        echo "Mozo: ".$this->mozo."\n";
        echo "Demora aprox.: ".$this->demora."\n";
        echo "-----------------------\n";
    }

    public static function listar($lista){
        foreach ($lista as $obj) {
            $obj->Mostar();
        }
    }

    public function validarProducto($p){
        $x = Producto::obtenerUno($p->id);
        if($x->cantidad > $p->cantidad){
                $p->precio = $p->precio;
                $p->tiempo = $x->tiempo;
                $p->sector = $x->sector;
                $p->idSector = $x->idSector;
                $p->descripcion = $x->descripcion;               
                return TRUE;
            }
        return FALSE;
    }
    
    static function generarCodigo() { 
        $c = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
        // echo $c;
        return  $c;
    }

    public function guardarPic($foto){
        $dir_subida = 'ImagenesDeMesas/';
        if (!file_exists($dir_subida)) {
            mkdir('ImagenesDeMesas/', 0777, true);    
        }
        $extension = explode(".", $foto["name"]);
        
        $destino = $dir_subida.$this->codigo."-".$this->mesa." - ".$this->mozo."_".$this->fecha.".".end($extension);
    
        if(move_uploaded_file($foto["tmp_name"],$destino)){
            echo "\nArchivo movido con exito en destino!\n  ";
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

    public static function validarEstado($x){   
        switch ($x) {
            case "Pendiente":
                return 0; 
                break;
            case "En preparacion":
                return 1; 
                break;
            case "Listo para servir":
                return 2; 
                break;
            case "Completado":
                return 3; 
                break;
            default:
                return 9;
                break;
        }
    }
    //endregion
}




//DEPRECADO
// public static function obtenerPendientes(){
    //     $objAccesoDatos = AccesoDatos::obtenerInstancia();
    //     $query = "SELECT * FROM pedidos WHERE pedidos.idEstado = 0 GROUP BY pedidos.codigo";
    //     $consulta = $objAccesoDatos->prepararConsulta($query);
    //     $consulta->execute();

    //     return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    // }