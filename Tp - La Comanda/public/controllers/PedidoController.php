<?php
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable{

    public function CargarUno($request, $response, $args){
        echo "Pedidos / Alta\n";
        $parametros = $request->getParsedBody();
        $fecha = new datetime("now");
        $foto = $_FILES['foto']; 

        if($parametros != NULL){
          $x = new Pedido();
          $x->codigo = Pedido::generarCodigo();
          
          $p = new Producto(); 
          $p->id = $parametros['producto'];
          $p->cantidad = $parametros['cantidad'];
          if(Pedido::validarProducto($p)){
            $x->producto = $p->id;
            $x->cantidad = $p->cantidad;
            $x->idSector = $p->idSector;
          };         
          $x->mesa = $parametros['mesa'];
          $x->mozo = $parametros['mozo'];
          $x->demora = 0; // lo cambian cuando van dando el "en prep"
          $x->fecha = $fecha->format("Y-m-d");
          if($foto['size'] > 0){
            $msg = $x->guardarPic($_FILES['foto']);
          } else $msg = "\n No hay foto disponible!"; 
          $x->estado = "Pendiente";
          $x->idEstado = 0;

          $r = $x->crearPedido();
          // $r = 0;// p forzar estados
            if($r > 0){
              $msg = "Codigo de pedido: ".$x->codigo;
              $payload = json_encode(array("mensaje" => "Pedido creado con exito!".$msg));
              // $x->Mostrar();
            } else {
              $payload = json_encode(array("Error" => "Faltan datos!".$msg));
            }
        } else {
          $payload = json_encode(array("Error" => "El pedido no se pudo crear!"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function AgregarProducto($request, $response, $args){
      echo "Pedidos / Agregar\n";
      $parametros = $request->getParsedBody();
      $codigo = $parametros['codigo'];

      if($parametros != NULL){
        $x = Pedido::ObtenerUno($codigo);
        
        $p = new Producto(); 
        $p->id = $parametros['producto'];
        $p->cantidad = $parametros['cantidad'];
        if(Pedido::validarProducto($p)){
          $x->producto = $p->id;
          $x->cantidad = $p->cantidad;
        };         
        var_dump($x);
                
        $r = $x->crearPedido();
        // $r = 0;// p forzar estados
          if($r > 0){
            $payload = json_encode(array("mensaje" => "Producto agregado con exito!"."Codigo de Pedido: ".$x->codigo));
            // $x->Mostrar();
          } else {
            $payload = json_encode(array("Error" => "Faltan datos!"));
          }
      } else {
        $payload = json_encode(array("Error" => "El producto no se pudo agregar!"));
      }
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
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
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args){
        $parametros = $request->getParsedBody();

        $item = $parametros['item'];
        $cantidad = $parametros['cantidad'];
        $precio = $parametros['precio'];
        $tiempo = $parametros['tiempo'];
        $id = $parametros['id'];

        // $x->demora = Pedido::calcularDemora($lista); esto queda p solo cocinero
          // $x->monto = Pedido::calcularMonto($lista); y esto p solo Mozo


        Pedido::modificar($id);

        $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ActualizarPedido($request, $response, $args){
      $parametros = $request->getParsedBody();
      $payload = json_encode(array("mensaje" => "Faltan Datos!"));
      
      $estado = $parametros['estado'];
      $codigo = $parametros['codigoPedido'];
      $idProducto = $parametros['idProducto'];
      $idUsuario = $parametros['idUsuario'];
      $fecha = new datetime("now");

      $producto = Producto::obtenerUno($idProducto);
      $empleado = Empleado::obtenerUno($idUsuario);
      $lista = Pedido::obtenerPorCodigo($codigo);
      $e = Pedido::validarEstdo($estado);
      if($e>=0 && $e<3  && $empleado != NULL && $lista != NULL){
        foreach ($lista as $p) { 
          if($producto->id == $p->producto && $p->idSector == $empleado->idSector){
            $p->estado = $estado; //echo $p->estado;
            $p->idEstado = 
            $p->fecha = $fecha->format("Y-m-d");
            $p->demora = ($producto->tiempo) * $p->cantidad;
            $p->modificar();
            $p->mostrarPedido();
            $payload = json_encode(array("mensaje" => "Pedido: ".$codigo." modificado con exito: ".$estado));
          } else {
            $payload = json_encode(array("mensaje" => "No se puede realzar esta accion!"));
          }
        } 
      } 
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  }

    public function TraerPedido($request, $response, $args){
      $codigo = $args['codigoPedido'];

      var_dump($codigo);
      $lista = Pedido::obtenerUnoPorCodigo($codigo);
      $demoraTotal=0;
      if($lista != NULL){
        foreach ($lista as $p) {
          echo "Item: ".(Producto::obtenerUno($p->producto))->descripcion."\n";
          echo "Estado: ".$p->estado."\n";
          $demoraTotal = $demoraTotal + $p->demora;        
        }
        $payload = json_encode(array("mensaje" => "Demora: ".$demoraTotal." minutos"));
      } else { 
        $payload = json_encode(array("mensaje" => "Estamos trabajando en su pedido. Aguarde un momento..."));
      }
      // $x->demora = Pedido::calcularDemora($lista); esto queda p solo cocinero     

      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
    }

    public function servirListos($request, $response, $args){
      $lista = Pedido::obtenerPorEstado(2);
      $fecha = new datetime("now");

      foreach ($lista as $p) {
        $m = Mesa::obtenerUno($p->mesa);
        $m->estado = "con cliente comiendo";
        $m->idEstado = 2;
        $m->modificar();

        $p->estado = "Completado";
        $p->idEstado = 3;
        $p->fecha = $fecha->format("Y-m-d");
        $p->modificar();
      }
      // var_dump($lista);
      if($lista != NULL){
        $payload = json_encode(array("lista" => $lista));
        echo "PEDIDOS SERVIDOS!\n";
      } else {
        $payload = json_encode(array("Error" => "No hay pedidos listos para servir!"));
      }
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerPendientes($request, $response, $args){
      $lista = Pedido::obtenerPorEstado(0);

      // var_dump($lista);
      if($lista != NULL){
        $payload = json_encode(array("lista" => $lista));
      } else {
        $payload = json_encode(array("Error" => "No hay datos disponibles!"));
      }
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerPendientesCocina($request, $response, $args){
      $lista = Pedido::obtenerPendientesCocina();

      // var_dump($lista);
      if($lista != NULL){
        $payload = json_encode(array("lista" => $lista));
      } else {
        $payload = json_encode(array("Error" => "No hay datos disponibles!"));
      }
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerPendientesBartender($request, $response, $args){
      $lista = Pedido::obtenerPendientesBartender();

      // var_dump($lista);
      if($lista != NULL){
        $payload = json_encode(array("lista" => $lista));
      } else {
        $payload = json_encode(array("Error" => "No hay datos disponibles!"));
      }
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
    }

  //   public function TraerUno($request, $response, $args){
  //     // Buscamos CriptoMoneda por id
  //     $x = $args['id'];
  //     $obj = CriptoMoneda::obtenerUno($x);
  //     if($obj != NULL){
  //       $payload = json_encode($obj);
  //     } else {
  //       $payload = json_encode(array("error" => "No hay datos!"));
  //     }

  //     $response->getBody()->write($payload);
  //     return $response->withHeader('Content-Type', 'application/json');
  // }





















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