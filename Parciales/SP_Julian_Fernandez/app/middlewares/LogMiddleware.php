<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once('./models/Cliente.php');
require_once('./models/Usuario.php');

class LogMiddleware
{
    private $tiposPermitidos = [];

    /**
     * Example middleware invokable class
     *
     * @param  ServerRequest  $request PSR-7 request
     * @param  RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);
        $OGresponse = json_decode($response->getBody());

        $response = new Response();

        $fechaRegistro = date('Y-m-d H:i:s');
        $uri = (string)$request->getUri();
        $method = $request->getMethod();
        // $test = ;

        $OGresponse->fecha_registro = $fechaRegistro;
        $OGresponse->URI = $uri;
        $OGresponse->method = $method;

        // grabado a tabla
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO log_accesos 
                (fecha_registro, uri, method)
            VALUES (:fecha_registro, :uri, :method)");
        $consulta->bindValue(':fecha_registro', $fechaRegistro);
        $consulta->bindValue(':uri', $uri, PDO::PARAM_STR);
        $consulta->bindValue(':method', $method, PDO::PARAM_STR);
        $consulta->execute();
        
        // $OGresponse->TEST = $test;

        $payload = json_encode($OGresponse);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

}
