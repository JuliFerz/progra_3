<?php

require_once './models/Usuario.php';

class UsuarioController
{

    public function CargarUno($request, $response, $args)
    {
        try {
            $parametros = $request->getParsedBody();

            $user = $parametros['usuario'];
            $clave = $parametros['clave'];
            $rol = $parametros['rol'];

            $usuario = new Usuario();
            $usuario->setUsuario($user);
            $usuario->setClave($clave);
            $usuario->setRol($rol);

            $id = $usuario->crearUsuario();
            $payload = json_encode(array("mensaje" => "Usuario $id creado con exito"));
        } catch (Exception $err) {
            $payload = json_encode(array("error" => $err->getMessage()));
            $response = $response->withStatus(500);
        } finally {
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
}

?>