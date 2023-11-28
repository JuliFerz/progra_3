<?php

require_once './interfaces/IApiUsable.php';
require_once './models/Cliente.php';
require_once './models/Reserva.php';

class JSONController
{
    public function DescargarEntidad($request, $response, $args)
    {
        try {
            $data = [];
            $queryParams = $request->getQueryParams();
            $entidad = isset($queryParams['entidad'])
                ? $queryParams['entidad']
                : false;

            if (!$entidad) {
                throw new Exception('Para descargar una entidad debe especificarla como parametro.');
            }

            switch ($entidad) {
                case 'clientes':
                    $bdClientes = Cliente::obtenerTodosCSV();
                    $data = Cliente::DescargarClientesJSON($bdClientes);
                    break;
                case 'reservas':
                    $bdReservas = Reserva::obtenerTodosCSV();
                    $data = Reserva::DescargarReservasJSON($bdReservas);
                    break;
                default:
                    throw new Exception('Entidad no contemplada.');
            }

            $response->getBody()->write($data);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (Exception $err) {
            $payload = json_encode(['error' => $err->getMessage()]);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public function CargarEntidad($request, $response, $args)
    {
        try {
            $archivos = $request->getUploadedFiles();
            $queryParams = $request->getQueryParams();

            $archivo = $archivos['archivo'];
            $entidad = isset($queryParams['entidad'])
                ? $queryParams['entidad']
                : false;
            $dir = '';

            if (!$entidad) {
                throw new Exception('Para cargar una entidad debe especificarla como parametro.');
            }

            switch ($entidad) {
                case 'clientes':
                    $dir = 'clientes';
                    Cliente::CargarUsuariosJSON($archivo->getStream()->getContents());
                    break;
                case 'reservas':
                    $dir = 'reservas';
                    Reserva::CargarReservasJSON($archivo->getStream()->getContents());
                    break;
                default:
                    throw new Exception('Entidad no contemplada.');
            }

            $payload = json_encode(["mensaje" => "Se cargo correctamente la entidad $dir desde el JSON en la base de datos"]);
        } catch (Exception $err) {
            $payload = json_encode(['error' => $err->getMessage()]);
        } finally {
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

}

?>