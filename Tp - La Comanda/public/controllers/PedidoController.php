<?php
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable{

    public function CargarUno($request, $response, $args){
        $parametros = $request->getParsedBody();
        $lista = Array();

        $p1 = new Producto(); //public function Setter($i,$q,$d,$p,$t){
        $p1->Setter($parametros['producto1'],$parametros['cantidad1'],"","","");
        array_push($lista, $p1);
        
        $p2 = new Producto(); //public function Setter($i,$q,$d,$p,$t){
        $p2->Setter($parametros['producto2'],$parametros['cantidad2'],"","","");
        array_push($lista, $p2);

        $p3 = new Producto(); //public function Setter($i,$q,$d,$p,$t){
        $p3->Setter($parametros['producto3'],$parametros['cantidad3'],"","","");
        array_push($lista, $p3);

        $p4 = new Producto(); //public function Setter($i,$q,$d,$p,$t){
        $p4->Setter($parametros['producto4'],$parametros['cantidad4'],"","","");
        array_push($lista, $p4);
        // Producto::Listar($lista);
        Pedido::validarLista($lista);

        $mesa = $parametros['mesa'];
        $mozo = $parametros['mozo'];

        // Creamos el Pedido
        $x = new Pedido();
        $x->items = $lista;
        $x->estado = "Pendiente";
        $x->mesa = $mesa;
        $x->mozo = $mozo;
        $x->demora = Pedido::calcularDemora($lista);
        $x->monto = Pedido::calcularMonto($lista);
        $x->crearPedido();

        $payload = json_encode(array("mensaje" => "Pedido creado con exito!"));
        $x->Mostrar();

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args){
        // Buscamos Pedido por id
        $id = $args['id'];
        $x = Pedido::obtenerUno($id);
        $payload = json_encode($id);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTipo($request, $response, $args){
      // Buscamos Pedido por Tipo
      $tipo = $args['tipo'];
      $x = Pedido::obtenerTipo($tipo);
      $payload = json_encode($tipo);

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
  }

    public function TraerTodos($request, $response, $args){
        $lista = Pedido::obtenerTodos();
        Pedido::listar($lista);
        $payload = json_encode(array("listaProducto" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args){
        $parametros = $request->getParsedBody();

        $item = $parametros['item'];
        $cantidad = $parametros['cantidad'];
        $precio = $parametros['precio'];
        $tiempo = $parametros['tiempo'];
        $id = $parametros['id'];

        Pedido::modificar($id);

        $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args){
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];
        Pedido::borrarUno($id);

        $payload = json_encode(array("mensaje" => "Pedido borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}