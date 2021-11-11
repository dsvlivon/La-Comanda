<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable{

    public function CargarUno($request, $response, $args){
        $parametros = $request->getParsedBody();

        $mozo = $parametros['mozo'];
        // $codigo = $parametros['codigo'];
        // $estado = $parametros['estado'];
        // $pedido = $parametros['pedido'];
        // $encuesta = $parametros['encuesta'];
        // $id = $parametros['id'];

        // echo "moso n°: ".$mozo;

        // Creamos el Mesa
        $x = new Mesa();
        if(Mesa::validarMozo($mozo)){ $x->mozo = $mozo; }
        // else{$x->mozo = 1;}
        $x->codigo = Mesa::generarCodigo();
        $x->estado = "Libre";
        $x->encuesta = NULL;
        $x->pedido = NULL;

        $x->crearMesa();

        $payload = json_encode(array("mensaje" => "Mesa creado con exito!"));
        $x->Mostrar();

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
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
        $lista = Mesa::obtenerTodos();
        Mesa::listar($lista);
        $payload = json_encode(array("listaProducto" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
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
}