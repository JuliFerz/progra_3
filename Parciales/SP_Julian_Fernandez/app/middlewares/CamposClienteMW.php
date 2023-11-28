<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class CamposClienteMW
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
            if (!isset($parametros['usuario']) 
                    || !isset($parametros['clave']) 
                    || !isset($parametros['nombre']) 
                    || !isset($parametros['apellido']) 
                    || !isset($parametros['email']) 
                    || !isset($parametros['tipo_doc']) 
                    || !isset($parametros['nro_doc']) 
                    || !isset($parametros['tipo_cliente']) 
                    || !isset($parametros['pais']) 
                    || !isset($parametros['ciudad']) 
                    || !isset($parametros['telefono'])){
                throw new Exception('No estan presentes todos los campos para la creacion de un cliente');
            } else if (gettype($parametros['usuario']) != 'string'
                    || gettype($parametros['clave']) != 'string'
                    || gettype($parametros['nombre']) != 'string'
                    || gettype($parametros['apellido']) != 'string'
                    || gettype($parametros['email']) != 'string'
                    || gettype($parametros['tipo_doc']) != 'string'
                    || gettype($parametros['tipo_cliente']) != 'string'
                    || gettype($parametros['pais']) != 'string'
                    || gettype($parametros['ciudad']) != 'string'){
                throw new Exception('Los datos recibidos no cumplen con el formato correcto.');
            }
            $this->validateNumber($parametros['nro_doc']);
            $this->validateNumber($parametros['telefono']);
            $this->validarTipoCliente($parametros['tipo_cliente']);
            $this->validarTipoDocumento($parametros['tipo_doc']);
            $response = $handler->handle($request);
        } catch (Exception $err){
            $response = new Response();
            $response = $response->withStatus(500);
            $payload = json_encode(array('mensaje' => $err->getMessage()));
            $response->getBody()->write($payload);
        } finally {
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    private function validarTipoDocumento($tipoDoc){
        if (strtoupper($tipoDoc) !== 'DNI'
                && strtoupper($tipoDoc) !== 'LE'
                && strtoupper($tipoDoc) !== 'LC'
                && strtoupper($tipoDoc) !== 'PASAPORTE') {
            throw new Exception('El tipo de documento del cliente debe ser de tipo \'DNI\', \'LE\', \'LC\', o \'PASAPORTE\'');
        }
    }
    private function validarTipoCliente($tipoCliente){
        if (strtoupper($tipoCliente) != 'INDI' && strtoupper($tipoCliente) != 'CORPO') {
            throw new Exception('El cliente debe ser de tipo \'individual\' (INDI) o \'corporativo\' (CORPO)');
        }
    }
    private function validateNumber($str){
        if (!preg_match('/^[0-9]+$/', $str)) {
            throw new Exception('Se encontro un caracter no numerico.');
        }
    }
}
