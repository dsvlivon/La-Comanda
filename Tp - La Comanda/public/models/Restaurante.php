<?php
include_once "Mesa.php";
include_once "Empleado.php";

class Restaurante{
    public $id;//AI
    public $mesas;//(Array) lista de mesas
    public $empleados;//(Array) lista de empleados


    
    public function crearRestaurante(){
       $empleados = Empleado::ObtenerTodos();
       $mesas = Mesa::ObtenerTodos();

    }


}