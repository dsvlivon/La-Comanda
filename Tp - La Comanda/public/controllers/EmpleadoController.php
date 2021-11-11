<?php
require_once './models/Empleado.php';
require_once './interfaces/IApiUsable.php';

class EmpleadoController extends Empleado implements IApiUsable{

    public function CargarUno($request, $response, $args){
      echo "Llega a CargarUno!\n";
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $clave = $parametros['clave'];
        $tipo = $parametros['tipo'];
        $sector = $parametros['sector'];

        // Creamos el empleado
        $x = new Empleado();
        $x->nombre = $nombre;
        $x->tipo = $tipo;
        $x->sector = $sector;
        $x->clave = $clave;
        $x->crearEmpleado();

        $payload = json_encode(array("mensaje" => "Empleado creado con exito.</br>Nomre: ".$nombre."</br>Tipo: ".$tipo."</br>Sector: ".$sector));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args){
        // Buscamos empleado por id
        $id = $args['id'];
        $x = Empleado::obtenerUno($id);
        $payload = json_encode($id);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args){
        $lista = Empleado::obtenerTodos();
        Empleado::listar($lista);
        $payload = json_encode(array("listaEmpleado" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args){
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $clave = $parametros['clave'];
        $tipo = $parametros['tipo'];
        $sector = $parametros['sector'];
        $id = $parametros['id'];

        Empleado::modificar($id);

        $payload = json_encode(array("mensaje" => "Empleado modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args){
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];
        Empleado::borrarUno($id);

        $payload = json_encode(array("mensaje" => "Empleado borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function LogIn($request, $response, $args){
      echo "La putra q t pario!";
      $parametros = $request->getParsedBody();
  
      $id = $parametros['id'];
      $clave = $parametros['clave'];

      $e = new Empleado();
      $e->id = $id;
      $e->clave = $clave;

      if(Empleado::ValidarEmpleado($e)){ //deberia volver el Emp completo
      
        $datos = array('empleado' => $e->nombre, 'tipo' => $e->tipo);
        $token = AutentificadorJWT::CrearToken($datos);
        $payload = json_encode(array('jwt' => $token));
      } else {
        $payload = json_encode(array('error' => 'No existe el empleado'));
        // echo "NO FUE POSIBLE"; 
      }
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
    }
}