<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class MWAccesos{
    public function ValidarToken(Request $request, RequestHandler $rHandler): Response{
        $header = $request->getHeaderLine('Authorization');
        $response = new Response();
        if (!empty($header)) {
            $token = trim(explode("Bearer", $header)[1]);
            AutentificadorJWT::VerificarToken($token);
            $response = $rHandler->handle($request);
        } else {
            $response->getBody()->write(json_encode(array("error" => "Falta ingresar el token")));
            $response = $response->withStatus(401);
        }
        return  $response->withHeader('Content-Type', 'application/json');
    }

    public function soloSocio(Request $request, RequestHandler $handler): Response{
        $header = $request->getHeaderLine('authorization');
        $response = new Response();
        if (!empty($header)) {
            $token = trim(explode("Bearer", $header)[1]);
            var_dump($token);
            $data = AutentificadorJWT::ObtenerData($token);
            if ($data->tipo == "Socio") {
                echo "Autorizado";
                $response = $handler->handle($request);
                $esValido = true;
            } else {
                echo "NO Autorizado";
                $response->getBody()->write(json_encode(array("error" => "Solo los Socios estan habilitados")));
                // $response = $response->withStatus(401);
            }
        } else {
            $response->getBody()->write(json_encode(array("error" => "Falta ingresar el token")));
            // $response = $response->withStatus(401);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function EsEmpleado(Request $request, RequestHandler $handler): Response{
        $header = $request->getHeaderLine('authorization');
        $response = new Response();
        if (!empty($header)) {
            $token = trim(explode("Bearer", $header)[1]);
            $data = AutentificadorJWT::ObtenerData($token);
            if ($data->tipo == "Empleado") {
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write(json_encode(array("error" => "Acceso para empleados")));
                $response = $response->withStatus(401);
            }
        } else {
            $response->getBody()->write(json_encode(array("error" => "Falta ingresar el token")));
            $response = $response->withStatus(401);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function socioPass(Request $request, RequestHandler $handler): Response{
        $header = $request->getHeaderLine('authorization');
        $response = new Response();
        if (!empty($header)) {
            $token = trim(explode("Bearer", $header)[1]);
            $data = AutentificadorJWT::ObtenerData($token);
            // var_dump($token);
            if ($data->tipo == "Socio") {
                // echo "Autorizado!!!\n";
                $response = $handler->handle($request);
            } else {
                // echo "NO Autorizado";
                $response->getBody()->write(json_encode(array("error" => "solo los socios tienen permiso")));
                $response = $response->withStatus(401);
            }
        } else {
            $response->getBody()->write(json_encode(array("error" => "Falta ingresar el token")));
            $response = $response->withStatus(401);
            // return $response->withHeader('Content-Type', 'application/json');
        }
        return $response->withHeader('Content-Type','application/json');
    }
    
}
