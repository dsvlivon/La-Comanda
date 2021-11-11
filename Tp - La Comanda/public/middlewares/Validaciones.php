<?php

class ValidacionesMW{

    public static function validarUser($request, $requestHandler){
    
        $antes = "al inicio... ";
        var_dump($antes);
      
        if($request->getMethod()==='GET'){
          $respuesta = "no se necesita credenciales => GET";
        } else if($request->getMethod() === 'POST'){
          $respuesta = "respuesta ELSE IF credenciales => POST";
        } else{
          $respuesta = "respuesta ELSE credenciales";
        }
      
      $response = $handler->handle($request);
      $dps = " Despues: ";
      
      $contenido = (string) $response->getBody();
      
      $response->getBody()->write("{$respuesta}</br></br></br>{$antes} - {$contenido}.... </br> {$dps}");
      
      return $response;
    }


}