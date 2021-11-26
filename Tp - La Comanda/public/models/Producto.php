<?php

class Producto{
    public $id;
    public $descripcion;//(STR) 
    public $cantidad;//(INT) value numerico
    public $precio;//(INT) value numerico
    public $tiempo;//(STR) DateTime?
    public $sector;//(STR) value str 
    public $idSector;

    //region ABM
    public function crearProducto(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos (descripcion, cantidad, precio, tiempo, sector, idSector) 
                                                            VALUES (:descripcion, :cantidad, :precio, :tiempo, :sector, :idSector)");
        // $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(':tiempo', $this->tiempo, PDO::PARAM_INT);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':idSector', $this->idSector, PDO::PARAM_STR);

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
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos WHERE sector = :sector");
        $consulta->bindValue(':sector', $x, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public static function modificar($id){
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE productos SET 
        descripcion = :descripcion, cantidad = :cantidad, precio = :precio, tiempo = :tiempo, sector = :sector, idSector = :idSector
        WHERE id = :id");
        
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(':tiempo', $this->tiempo, PDO::PARAM_INT);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':idSector', $this->idSector, PDO::PARAM_STR);
        
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
    function Mostrar(){
        echo "Descripcion: ".$this->descripcion."\n";
        echo "Precio: ".$this->precio."\n";
        echo "Sector: ".$this->sector."\n";
        echo "idSector: ".$this->idSector."\n";
        echo "-----------------------\n";
    }

    public static function validarIdSector($x){
        switch ($x) {
            case "Cerveza":
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
            default:
                return 6;
                break;
        }
    }

    public static function Listar($lista){
        foreach ($lista as $obj) {
            echo "<ul>";
            echo "<li>"."Descripcion: ".$obj->descripcion."</li>";
            echo "<li>"."Precio: ".$obj->precio."</li>";
            // echo "<li>"."Tipo: ".$obj->sector."</li>";
            // echo "<li>"."Sector: ".$obj->sector."</li>";
            echo "</ul>";
        }
    }
    //endregion
}