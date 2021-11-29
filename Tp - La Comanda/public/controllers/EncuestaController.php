<?php
require_once './models/Encuesta.php';
require_once './interfaces/IApiUsable.php';

class EncuestaController extends Encuesta{

  public function CargarUno($request, $response, $args){
      $parametros = $request->getParsedBody();
      $payload = json_encode(array("mensaje" => "Faltan datos"));

      $mozo = $parametros['valoracionMozo'];
      $mesa = $parametros['valoracionMesa'];
      $restaurante = $parametros['valoracionRestaurante'];
      $cocinero = $parametros['valoracionCocinero'];        

      $comentarios = $parametros['comentarios'];
      $codigo = $parametros['codigoPedido']; //echo $codigo;
      
      // Creamos el Encuesta
      if($parametros != NULL){ 
        $x = new Encuesta();
        $p = Pedido::obtenerUnoPorCodigo($codigo); //var_dump($p);
        $m = Mesa::ObtenerUno($p->mesa); //var_dump($m);

        if($m->estado == 0 && $p){
          
          $x->mozo = Encuesta::validarPuntuacion($mozo);
          $x->mesa = Encuesta::validarPuntuacion($mesa);
          $x->cocinero = Encuesta::validarPuntuacion($cocinero);
          $x->restaurante = Encuesta::validarPuntuacion($restaurante);
        
          $x->comentarios = $comentarios;
          $x->codigo = $codigo;

          $x->Mostrar();
          $x->crearEncuesta();
          $payload = json_encode(array("mensaje" => "Encuesta creado con exito!. Gracias por su tiempo!"));
          } else {
            $payload = json_encode(array("mensaje" => "No se pudo crear la Encuesta, la mesa sigue abierta!"));
          }
      }
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args){
      // Buscamos Encuesta por id
      $codigo = $args['codigo'];
      $x = Encuesta::obtenerUno($codigo);
      $payload = json_encode($codigo);

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args){
      $lista = Encuesta::obtenerTodos();
      // Encuesta::listar($lista);
      $payload = json_encode(array("listaProducto" => $lista));

      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  }

  public function obtenerMejoresComentarios($request, $response, $args){
    $payload = json_encode(array("error" => "No hay datos disponibles!"));
    $lista = Encuesta::obtenerPorPromedio(4);
    $aux = array();

    if($lista != NULL){
      foreach ($lista as $c) {
        array_push($aux, $c->comentarios);
      }
      $payload = json_encode(array("listaProducto" => $aux));
    } 
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }
  
  
}