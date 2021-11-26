<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto implements IApiUsable{

    public function CargarUno($request, $response, $args){
        $parametros = $request->getParsedBody();

        $descripcion = $parametros['descripcion'];
        $cantidad = $parametros['cantidad'];
        $precio = $parametros['precio'];
        $tiempo = $parametros['tiempo'];
        $sector = $parametros['sector'];
        
        // Creamos el Producto
        $x = new Producto();
        $x->descripcion = $descripcion;
        $x->precio = $precio;
        $x->tiempo = $tiempo;
        $x->cantidad = $cantidad;
        $x->sector = $sector;
        $x->idSector = Producto::validarIdSector($sector);
        $r = $x->crearProducto();
        // $r = 0;// p forzar los estados
        if($r > 0){
          $payload = json_encode(array("mensaje" => "Producto creado con exito!"));
          $x->Mostrar();
        } else {
          $payload = json_encode(array("mensaje" => "NO se pudo crear el producto!"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args){
        // Buscamos Producto por id
        $id = $args['id'];
        $x = Producto::obtenerUno($id);
        $payload = json_encode($id);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTipo($request, $response, $args){
      // Buscamos Producto por Tipo
      $tipo = $args['tipo'];
      $x = Producto::obtenerTipo($tipo);
      $payload = json_encode($tipo);

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
  }

    public function TraerTodos($request, $response, $args){
        $lista = Producto::obtenerTodos();
        Producto::listar($lista);
        $payload = json_encode(array("listaProducto" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args){
        $parametros = $request->getParsedBody();

        $descripcion = $parametros['descripcion'];
        $cantidad = $parametros['cantidad'];
        $precio = $parametros['precio'];
        $tiempo = $parametros['tiempo'];
        $id = $parametros['id'];

        Producto::modificar($id);

        $payload = json_encode(array("mensaje" => "Producto modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args){
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];
        Producto::borrarUno($id);

        $payload = json_encode(array("mensaje" => "Producto borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}