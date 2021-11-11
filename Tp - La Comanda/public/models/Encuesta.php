<?php

class Encuesta{
    public $id;//AI
    public $mozo;//(INT) codigo propio
    public $comida;//(INT) Valoracion (1-5)
    public $cervezas;//(INT) Valoracion (1-5)
    public $tragosYvinos;//(INT) Valoracion (1-5)
    public $candyBar;//(INT) Valoracion (1-5)
    public $comentarios;//(STR) txt d opinion

    public function crearEncuesta(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO Encuestas 
        (mozo, comida, cervezas, tragosYvinos, candyBar, comentarios) 
        VALUES (:mozo, :comida, :cervezas, :tragosYvinos, :candyBar, :comentarios)");
        $consulta->bindValue(':mozo', $this->mozo, PDO::PARAM_INT);
        $consulta->bindValue(':comida', $this->comida, PDO::PARAM_INT);
        $consulta->bindValue(':cervezas', $this->cervezas, PDO::PARAM_INT);
        $consulta->bindValue(':tragosYvinos', $this->tragosYvinos, PDO::PARAM_INT);
        $consulta->bindValue(':candyBar', $this->candyBar, PDO::PARAM_INT);
        $consulta->bindValue(':comentarios', $this->comentarios, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM encuestas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }

    public static function obtenerUno($id){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM encuestas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Encuesta');
    }

    public static function modificar($id){
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas 
        SET codigo = :codigo, pedido = :pedido, estado = :estado, mozo = :mozo, encuesta = :encuesta
        WHERE id = :id");
        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_INT);
        $consulta->bindValue(':pedido', $this->clave, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->id, PDO::PARAM_STR);
        $consulta->bindValue(':mozo', $this->usuario, PDO::PARAM_INT);
        $consulta->bindValue(':encuesta', $this->usuario, PDO::PARAM_INT);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        
        $consulta->execute();
    }

    public static function borrar($id){
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE encuestas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }
}
