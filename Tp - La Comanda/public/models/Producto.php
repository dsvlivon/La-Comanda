<?php

class Producto{
    public $id;
    public $descripcion;//(STR) 
    public $cantidad;//(INT) value numerico
    public $precio;//(INT) value numerico
    public $tiempo;//(STR) DateTime?
    public $tipo;//(STR) value str 


    //region ABM
    public function crearProducto(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos (descripcion, cantidad, precio, tiempo, tipo) 
                                                            VALUES (:descripcion, :cantidad, :precio, :tiempo, :tipo)");
        // $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(':tiempo', $this->tiempo, PDO::PARAM_INT);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function obtenerUno($id){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public static function obtenerTipo($x){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos WHERE tipo = :tipo");
        $consulta->bindValue(':tipo', $x, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public static function modificar($id){
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE productos SET 
        descripcion = :descripcion, cantidad = :cantidad, precio = :precio, tiempo = :tiempo, tipo = :tipo
        WHERE id = :id");
        
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(':tiempo', $this->tiempo, PDO::PARAM_INT);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrar($id){
        $objAccesoDato = AccesoDatos::obtenerInstancia();
  
        $consulta = $objAccesoDato->prepararConsulta("DELETE productos WHERE id = :id");
        $consulta->bindValue(':id', $producto, PDO::PARAM_INT);
        $consulta->execute();
    }
    //endregion

    //region Propias
    public function Setter($i,$q,$d,$p,$t){
        if($i!=NULL){$this->id = $i;}
        if($q!=NULL){$this->cantidad = $q;}
        if($d!=NULL){$this->descripcion = $d;}
        if($p!=NULL){$this->precio = $p;}
        if($t!=NULL){$this->tiempo = $t;}
    }
    function Mostrar(){
        echo "Descripcion: ".$this->descripcion."\n";
        echo "Precio: ".$this->precio."\n";
        // echo "Tiempo: ".$this->tiempo."</br>"; //ningun resto t dice cuanto tarda :P
        // echo "Cantidad: ".$this->cantidad."</br>"; //ni cuantas tiene
        echo "-----------------------\n";
    }

    public static function Listar($lista){
        foreach ($lista as $obj) {
            echo "<ul>";
            echo "<li>"."Descripcion: ".$obj->descripcion."</li>";
            echo "<li>"."Precio: ".$obj->precio."</li>";
            // echo "<li>"."Tipo: ".$obj->tipo."</li>";
            // echo "<li>"."Sector: ".$obj->sector."</li>";
            echo "</ul>";
        }
    }
    //endregion
}