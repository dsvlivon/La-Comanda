<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class MWAccesos{
    public function soloSocio(Request $request, RequestHandler $handler){
        $header = $request->getHeaderLine('authorization');
        $response = new Response();
        if (!empty($header)) {
            $token = trim(explode("Bearer", $header)[1]);
            // var_dump($token);
            $data = AutentificadorJWT::ObtenerData($token);
            if ($data->tipo == "Socio") {
                echo $data->tipo." Autorizado\n";
                $response = $handler->handle($request);
            } else {
                // echo "NO Autorizado";
                $response->getBody()->write(json_encode(array("error" => "Solo los Socio estan habilitados")));
                $response = $response->withStatus(401);
            }
        } else {
            $response->getBody()->write(json_encode(array("error" => "Falta ingresar el token")));
            $response = $response->withStatus(401);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function soloSocio_Mozo(Request $request, RequestHandler $handler){
        $header = $request->getHeaderLine('authorization');
        $response = new Response();
        if (!empty($header)) {
            $token = trim(explode("Bearer", $header)[1]);
            // var_dump($token);
            $data = AutentificadorJWT::ObtenerData($token);
            if ($data->tipo == "Socio" || $data->tipo == "Mozo") {
                echo $data->tipo." Autorizado\n";
                $response = $handler->handle($request);
            } else {
                // echo "NO Autorizado";
                $response->getBody()->write(json_encode(array("error" => "Usuario habilitados")));
                $response = $response->withStatus(401);
            }
        } else {
            $response->getBody()->write(json_encode(array("error" => "Falta ingresar el token")));
            $response = $response->withStatus(401);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function soloCocinero(Request $request, RequestHandler $handler){
        $header = $request->getHeaderLine('authorization');
        $response = new Response();
        if (!empty($header)) {
            $token = trim(explode("Bearer", $header)[1]);
            // var_dump($token);
            $data = AutentificadorJWT::ObtenerData($token);
            if ($data->tipo == "Cocinero") {
                echo $data->tipo." Autorizado\n";
                $response = $handler->handle($request);
            } else {
                // echo "NO Autorizado";
                $response->getBody()->write(json_encode(array("error" => "Solo los Cocinero estan habilitados")));
                $response = $response->withStatus(401);
            }
        } else {
            $response->getBody()->write(json_encode(array("error" => "Falta ingresar el token")));
            $response = $response->withStatus(401);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function soloBartender(Request $request, RequestHandler $handler){
        $header = $request->getHeaderLine('authorization');
        $response = new Response();
        if (!empty($header)) {
            $token = trim(explode("Bearer", $header)[1]);
            // var_dump($token);
            $data = AutentificadorJWT::ObtenerData($token);
            if ($data->tipo == "Bartender") {
                echo $data->tipo." Autorizado\n";
                $response = $handler->handle($request);
            } else {
                // echo "NO Autorizado";
                $response->getBody()->write(json_encode(array("error" => "Solo los Bartender estan habilitados")));
                $response = $response->withStatus(401);
            }
        } else {
            $response->getBody()->write(json_encode(array("error" => "Falta ingresar el token")));
            $response = $response->withStatus(401);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function soloMozo(Request $request, RequestHandler $handler){
        $header = $request->getHeaderLine('authorization');
        $response = new Response();
        if (!empty($header)) {
            $token = trim(explode("Bearer", $header)[1]);
            // var_dump($token);
            $data = AutentificadorJWT::ObtenerData($token);
            if ($data->tipo == "Mozo") {
                echo $data->tipo." Autorizado\n";
                $response = $handler->handle($request);
            } else {
                // echo "NO Autorizado";
                $response->getBody()->write(json_encode(array("error" => "Solo los Mozos estan habilitados")));
                $response = $response->withStatus(401);
            }
        } else {
            $response->getBody()->write(json_encode(array("error" => "Falta ingresar el token")));
            $response = $response->withStatus(401);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
}

