<?php
require_once './models/Venta.php';
// require_once './interfaces/IApiUsable.php';

class InformesController{

    // public function TraerUno($request, $response, $args){
    //     // Buscamos empleado por id
    //     $id = $args['id'];
    //     $x = Usuario::obtenerUno($id);
    //     $payload = json_encode($id);

    //     $response->getBody()->write($payload);
    //     return $response
    //       ->withHeader('Content-Type', 'application/json');
    // }
    
    // public function TraerTodos($request, $response, $args){
    //     $lista = Usuario::obtenerTodos();
    //     Usuario::listar($lista);
    //     $payload = json_encode(array("listaUsuario" => $lista));

    //     $response->getBody()->write($payload);
    //     return $response
    //       ->withHeader('Content-Type', 'application/json');
    // }

    public function TraerVentasPorFecha($request, $response, $args){
      $f1 = $args['f1'];
      $f2 = $args['f2'];

      $lista = Venta::TraerVentasPorFecha($f1, $f2);
      
      if (count($lista) > 0) {
        echo "Hortalizas Clima SECO vendidas entre ". $f1. " y ".$f2;
        $payload = json_encode(array("lista" => $lista));
      } else {
        $payload = json_encode(array("error" => "No hay datos en la lista"));
      } 

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
  }
  //TraerUsuariosPorCompra
  //8-GET)TTraer todos los usuarios que compraron zanahoria (o cualquier otra, buscada por nombre)->solo proveedor(JWT)

  public function TraerPorCompra($request, $response, $args){
    $h = $args['id'];

    $usuarios = Usuario::obtenerTodos();
    $lista = Usuario::TraerUsuariosPorCompra($h);
    
    if (count($lista) > 0) {
      echo "Usuarios que compraron ". $h;
      foreach ($lista as $obj) {
        foreach ($usuarios as $u) {
          if($obj->id == $u->mail) {
            $obj->mail = $u->mail;
          }  
        }
      }
      $payload = json_encode(array("lista" => $lista));
    } else {
      $payload = json_encode(array("error" => "No hay datos en la lista"));
    } 

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
}
    

}