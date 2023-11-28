<?php

require_once './utils/AutentificadorJWT.php';
// require_once './models/Sector.php';

use Firebase\JWT\JWT;

class AuthController
{
    public function GenerarToken($request, $response, $args)
    {
        $usuarioDisponible = $request->getAttribute('usuarioDisponible');
        $datos = [
            'id' => $usuarioDisponible->{'id'},
            'usuario' => $usuarioDisponible->{'usuario'},
            'rol' => strtolower($usuarioDisponible->{'rol'}),
        ];

        $token = AutentificadorJWT::CrearToken($datos);
        $payload = json_encode(array('token' => $token));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}

?>