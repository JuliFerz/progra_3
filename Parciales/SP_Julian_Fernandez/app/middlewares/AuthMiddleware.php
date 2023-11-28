<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once('./models/Cliente.php');
require_once('./models/Usuario.php');

class AuthMiddleware
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
        try {
            $header = $request->getHeaderLine('Authorization');
            $token = trim(explode("Bearer", $header)[1]);

            $data = AutentificadorJWT::ObtenerData($token);
            // if (!in_array(strtolower($data->{'tipo_cliente'}), $this->tiposPermitidos)) {
            if (!in_array(strtolower($data->{'rol'}), $this->tiposPermitidos)) {
                throw new Exception("No tiene permitido consumir el recurso");
            }
            $response = $handler->handle($request);

        } catch (Exception $e) {
            $response = new Response();
            $response = $response->withStatus(500);
            $payload = json_encode(["mensaje" => $e->getMessage()]);
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function validarLogin(Request $request, RequestHandler $handler): Response
    {
        try {
            $parametros = $request->getParsedBody();
            if (
                !isset($parametros['usuario']) || $parametros['usuario'] == null
                || !isset($parametros['clave']) || $parametros['clave'] == null
            ) {
                throw new Exception('No pudo iniciar sesion. Por favor, cargue el usuario y la clave.');
            }
            $nombreUsuario = $parametros['usuario'];
            $clave = $parametros['clave'];
            $usuarioDisponible = null;

            // $bdUsuarios = Cliente::obtenerTodos();
            $bdUsuarios = Usuario::obtenerTodos();
            foreach ($bdUsuarios as $usuario) {
                if ($usuario->{'usuario'} == $nombreUsuario && password_verify($clave, $usuario->{'clave'})) {
                    $usuarioDisponible = $usuario;
                }
            }
            if (!$usuarioDisponible) {
                throw new Exception('Usuario y/o clave incorrectos.');
            }
            $request = $request->withAttribute('usuarioDisponible', $usuarioDisponible);
            $response = $handler->handle($request);
        } catch (Exception $err) {
            $response = new Response();
            $response = $response->withStatus(500);
            $payload = json_encode(array('mensaje' => $err->getMessage()));
            $response->getBody()->write($payload);
        } finally {
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public static function verificarToken(Request $request, RequestHandler $handler): Response
    {
        try {
            $header = $request->getHeaderLine('Authorization');
            if (!$header) {
                throw new Exception("Falta envio de token para consumir recurso");
            }
            $token = trim(explode("Bearer", $header)[1]);

            AutentificadorJWT::VerificarToken($token);
            $response = $handler->handle($request);
        } catch (Exception $e) {
            $response = new Response();
            $response = $response->withStatus(500);
            $errMsg = $e->getMessage();
            $payload = json_encode(["mensaje" => "No se logro autenticar con el token. $errMsg"]);
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function obtenerDataToken(Request $request, RequestHandler $handler): Response
    {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);

        $request = $request->withAttribute('dataToken', $data);
        $response = $handler->handle($request);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getTiposPermitidos()
    {
        return $this->tiposPermitidos;
    }
    public function setTiposPermitidos($valor)
    {
        $this->tiposPermitidos = $valor;
    }
}
