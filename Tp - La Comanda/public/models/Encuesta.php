<?php

class Encuesta{
    public $id;//AI
    public $mozo;//(INT) codigo propio
    public $mesa;//(INT) Valoracion (1-5)
    public $restaurante;//(INT) Valoracion (1-5)
    public $cocinero;//(INT) Valoracion (1-5)
    public $comentarios;//(STR) txt d opinion
    public $codigo;

    public function crearEncuesta(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO encuestas 
        (mozo, mesa, restaurante, cocinero, comentarios, codigo) 
        VALUES (:mozo, :mesa, :restaurante, :cocinero, :comentarios, :codigo)");
        // var_dump($this);
        $consulta->bindValue(':mesa', $this->mesa, PDO::PARAM_INT);
        $consulta->bindValue(':mozo', $this->mozo, PDO::PARAM_INT);
        $consulta->bindValue(':restaurante', $this->restaurante, PDO::PARAM_INT);       
        $consulta->bindValue(':cocinero', $this->cocinero, PDO::PARAM_INT);

        $consulta->bindValue(':comentarios', $this->comentarios, PDO::PARAM_STR);
        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_STR);

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
        $consulta = $objAccesoDato->prepararConsulta("UPDATE encuestas 
        SET codigo = :codigo, pedido = :pedido, estado = :estado, mozo = :mozo, encuesta = :encuesta
        WHERE id = :id");
         $consulta->bindValue(':mesa', $this->mesa, PDO::PARAM_INT);
         $consulta->bindValue(':mozo', $this->mozo, PDO::PARAM_INT);
         $consulta->bindValue(':restaurante', $this->restaurante, PDO::PARAM_INT);
         $consulta->bindValue(':cocinero', $this->cocinero, PDO::PARAM_INT);
         $consulta->bindValue(':comentarios', $this->comentarios, PDO::PARAM_STR);
        
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        
        $consulta->execute();
    }

    public static function borrar($id){
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE encuestas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    function mostrar(){
        echo "Codigo: ".$this->codigo."\n";
        echo "Mozo: ".Encuesta::dibujar($this->mozo)."\n";
        echo "Mesa: ".Encuesta::dibujar($this->mesa)."\n";
        echo "Cocinero: ".Encuesta::dibujar($this->cocinero)."\n";
        echo "Restaurante: ".Encuesta::dibujar($this->restaurante)."\n";
        echo "Comentarios: ".$this->comentarios."\n";
        echo "-----------------------\n";
    }

    public static function validarPuntuacion($x){
        $p=0;
        if(is_numeric($x)){ 
            if($x > 10) { $p = 10; }
            else if($x < 1) { $p = 1; }
            else { $p = $x; }
        } else { 
            $p = 1; 
        }
        echo $p;        
        return $p;
    }

    public static function dibujar($x){
        $msg = "";
        for ($i=0; $i < $x; $i++) { 
            $msg = $msg."*";
        }
        return $msg;
    }

    public static function obtenerPorPromedio($x){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT encuestas.comentarios FROM encuestas WHERE ((encuestas.mozo+encuestas.mesa+encuestas.restaurante+encuestas.cocinero)/4)> :promedio");
        $consulta->bindValue(':promedio', $x, PDO::PARAM_INT);
        
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }
}
