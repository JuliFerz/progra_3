<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class CamposReservaMW
{
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
        try {
            $parametros = $request->getParsedBody();
            if (!isset($parametros['tipo_cliente']) 
                    || !isset($parametros['nro_cliente']) 
                    || !isset($parametros['fecha_entrada']) 
                    || !isset($parametros['fecha_salida']) 
                    || !isset($parametros['tipo_habitacion']) 
                    || !isset($parametros['importe_total'])){
                throw new Exception('No estan presentes todos los campos para la creacion de una reserva');
            } else if (gettype($parametros['tipo_cliente']) != 'string'
                    || gettype($parametros['tipo_habitacion']) != 'string'){
                throw new Exception('Los datos recibidos no cumplen con el formato correcto.');
            }
            $this->validarNumero($parametros['nro_cliente']);
            $this->validarNumero($parametros['importe_total']);
            $this->validarTipoCliente($parametros['tipo_cliente']);
            $this->validarFormatoFecha($parametros['fecha_entrada']);
            $this->validarFormatoFecha($parametros['fecha_salida']);
            $response = $handler->handle($request);
        } catch (Exception $err){
            $response = new Response();
            $payload = json_encode(array('mensaje' => $err->getMessage()));
            $response->getBody()->write($payload);
        } finally {
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    private function validarFormatoFecha($strDate)
    {
        if (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $strDate)) {
            throw new Exception("Los formatos fecha no coinciden. Formato valido: 'yyyy-dd-mm'");
        }
    }
    private function validarTipoCliente($tipoCliente){
        if (strtoupper($tipoCliente) != 'INDI' && strtoupper($tipoCliente) != 'CORPO') {
            throw new Exception('La reserva debe ser de tipo \'individual\' (INDI) o \'corporativo\' (CORPO)');
        }
    }
    private function validarNumero($str){
        if (!preg_match('/^[0-9]+$/', $str)) {
            throw new Exception('Se encontro un caracter no numerico.');
        }
    }
}
