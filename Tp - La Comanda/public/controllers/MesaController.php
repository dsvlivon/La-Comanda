<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable{

  public function CargarUno($request, $response, $args){
      $parametros = $request->getParsedBody();

      $mozo = $parametros['mozo'];
      
      // Creamos el Mesa
      $x = new Mesa();
      if(Mesa::validarMozo($mozo)){ 
        $x->mozo = $mozo; 
        $x->estado = "cerrada";
        $x->idEstado = 0;
        
        $x->crearMesa();
        $payload = json_encode(array("mensaje" => "Mesa creado con exito!"));
        $x->Mostrar();
      } else {
        $payload = json_encode(array("mensaje" => "No se pudo crear la Mesa!"));
      }


      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args){
      // Buscamos Mesa por id
      $codigo = $args['codigo'];
      $x = Mesa::obtenerUno($codigo);
      $payload = json_encode($codigo);

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args){
      $payload = json_encode(array("errer" => "No hay datos disponibles!"));
      $lista = Mesa::obtenerTodos();
      // Mesa::listar($lista);
      if($lista != NULL){
        $payload = json_encode(array("listaProducto" => $lista));
      }

      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  }
  
  public function ModificarUno($request, $response, $args){
      $parametros = $request->getParsedBody();

      $estado = $parametros['estado'];
      $pedido = $parametros['pedido'];
      $mozo = $parametros['mozo'];
      $encuesta = $parametros['encuesta'];
      $codigo = $parametros['codigo'];
      
      $id = $parametros['id'];

      Mesa::modificar($codigo);

      $payload = json_encode(array("mensaje" => "Mesa modificado con exito"));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args){
      $parametros = $request->getParsedBody();

      $id = $parametros['id'];
      Mesa::borrarUno($id);

      $payload = json_encode(array("mensaje" => "Mesa borrado con exito"));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
  }

  public function ActualizarMesa($request, $response, $args){
    $parametros = $request->getParsedBody();

    $payload = json_encode(array("error" => "Faltan datos!"));
    $estado = $parametros['estado']; 
    $id = $parametros['idMesa'];
    
    $m = Mesa::obtenerUno($id); //var_dump($m);
    $e = Mesa::validarEstado($estado); //var_dump($e);
    if($estado != NULL && $id != NULL){ 
      if($e > 0 && $e < 4){
          $m->estado = $estado;
          $m->idEstado = $e;
          $m->modificar();
          $payload = json_encode(array("mensaje" => "Mesa actualizada con exito!"));
      } else {
      $payload = json_encode(array("error" => "No se puede realizar esta accion!"));    
      }  
    }
    $response->getBody()->write($payload);
    return $response ->withHeader('Content-Type', 'application/json');
  }

  public function CerrarMesa($request, $response, $args){
    $parametros = $request->getParsedBody();

    $payload = json_encode(array("error" => "Faltan datos!"));
    $estado = $parametros['estado']; 
    $id = $parametros['idMesa'];
    
    $m = Mesa::obtenerUno($id); //var_dump($m);
    $e = Mesa::validarEstado($estado); var_dump($e);
    if($estado != NULL && $id != NULL){ 
      if($e >= 0 && $e < 4){
          $m->estado = $estado;
          $m->estado = $e; 
          $m->modificar();
          $payload = json_encode(array("mensaje" => "Mesa actualizada con exito!"));
      } else {
      $payload = json_encode(array("error" => "No se puede realizar esta accion!"));    
      }  
    }
    $response->getBody()->write($payload);
    return $response ->withHeader('Content-Type', 'application/json');
  }
}