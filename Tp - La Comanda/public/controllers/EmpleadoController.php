<?php
require_once './models/Empleado.php';
require_once './interfaces/IApiUsable.php';
require_once './controllers/Archivo.php';

class EmpleadoController extends Empleado implements IApiUsable{

    public function CargarUno($request, $response, $args){
      echo "Llega a CargarUno!\n";
        $parametros = $request->getParsedBody();

        // Creamos el empleado
        $x = new Empleado();
        $x->nombre = $parametros['nombre'];
        $x->clave = $parametros['clave'];
        $x->tipo = $parametros['tipo'];
        $x->sector = $parametros['sector'];

        $x->idTipo = Empleado::validarIdTipo($x->tipo);
        $x->idSector = Empleado::validarIdSector($x->sector);
        var_dump($x);
        $r = $x->crearEmpleado();
        // $r = 0;
        if($r>0){
          $payload = json_encode(array("mensaje" => "Empleado creado con exito.</br>Nomre: ".$x->nombre."</br>Tipo: ".$x->tipo."</br>Sector: ".$x->sector));
        } else { 
          $payload = json_encode(array("mensaje" => "No se pudo crear el empleado!"));
        }
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
        return $response->withHeader('Content-Type', 'application/json');
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
      $parametros = $request->getParsedBody();
        // $id = $parametros['id'];   
      $x= new Empleado();
      $x->id = $parametros['id'];
      $x->clave = $parametros['clave'];

      if(Empleado::ValidarUsuario($x)){ //deberia volver el Emp completo
      
        $datos = array('id' => $x->id,'nombre' => $x->nombre, 'tipo' => $x->tipo);
        $token = AutentificadorJWT::CrearToken($datos);
        $payload = json_encode(array('jwt' => $token));
        } else {
        $payload = json_encode(array('error' => 'No existe el usuario'));
        // echo "NO FUE POSIBLE"; 
      }
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
    }

    public static function validarIdSector($x){
      switch ($x) {
          case "Todos":
              return 0;
              break;
          case "cerveza":
              return 1;
              break;
          case "tragos":
              return 2;
              break;
          case "candyBar":
              return 3;
              break;
          case "cocina":
              return 4;
              break;
          case "mesas":
            return 5;
            break;

          default:
              return 6;//unType
              break;
      }
    }

    public static function GuardarCSV($request, $response, $args){
      $parametros = $request->getParsedBody();
      // $id = $parametros['id'];   
      $id = $parametros['idEmpleado'];
      if($id != NULL){
        $e = Empleado::obtenerUno($id);
        
        if($e != NULL){
          $archivo = 'ArchivoEmpleado/'.$e->id."-".$e->nombre.".csv";
          echo $archivo;

          $line = $e->id.",".$e->nombre.",".$e->idTipo.",".$e->tipo.",".$e->idSector.",".$e->sector;
                
          $r = Archivo::GuardarCSV($archivo,$line);
          // var_dump($r);
          
          if($r != NULL){
            $payload = json_encode(array("mensaje" => "Empleado Guardado con exito!"));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
          }

        } else {
          $payload = json_encode(array("mensaje" => "El empleado no existe!"));
        }
      }
      $payload = json_encode(array("mensaje" => "No se pudo Guardar el empleado!"));
      
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
    }

    public static function CargarCSV($request, $response, $args){
      $parametros = $request->getParsedBody();
      $archivo = $_FILES['archivo'];
      // var_dump($archivo);
      // echo $archivo["name"];
      
      if($archivo != NULL){
        $e = new Empleado();
        $obj = Archivo::LeerCSV('ArchivoEmpleado/'.$archivo["name"]);
        // var_dump($obj);
        $array = explode(",", $obj[0]);//4,Pedro,3,Mozo,5,Mesas
        // var_dump($array);
        $e->id = $array[0];
        $e->nombre = $array[1];
        $e->idTipo = $array[2];
        $e->tipo = $array[3];
        $e->idSector = $array[4];
        $e->sector = $array[5];
        $e->foto = "";

        $r = $e->CrearEmpleado();
        
        if($r>0){
          $payload = json_encode(array("mensaje" => "Empleado Cargado con exito!"));
          $e->Mostrar();
        } else {
          $payload = json_encode(array("mensaje" => "No se pudo Cargar el empleado!"));
        }
      } else {
        $payload = json_encode(array("mensaje" => "Faltan datos!"));
      }
      
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
    }
}