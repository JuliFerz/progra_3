<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once('./models/Cliente.php');
require_once('./models/Usuario.php');

class LogTransaccionMiddleware
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

        $dataToken = $request->getAttribute('dataToken');
        
        $response = new Response();
        $fechaRegistro = date('Y-m-d H:i:s');
        $id_usuario = $dataToken->{'id'};
        $usuario = $dataToken->{'usuario'};
        $numeroOperacion = $OGresponse->{'id'};

        $OGresponse->fecha_registro = $fechaRegistro;
        $OGresponse->id_usuario = $id_usuario;
        $OGresponse->usuario = $usuario;
        $OGresponse->numero_operacion = $numeroOperacion;

        // grabado a tabla
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO log_ajustes 
                (fecha_ajuste, id_usuario, usuario, numero_operacion)
            VALUES (:fecha_ajuste, :id_usuario, :usuario, :numero_operacion)");
        $consulta->bindValue(':fecha_ajuste', $fechaRegistro);
        $consulta->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->bindValue(':numero_operacion', (int)$numeroOperacion, PDO::PARAM_INT);
        $consulta->execute();

        $payload = json_encode($OGresponse);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

}
