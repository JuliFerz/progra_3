<?php

require_once './interfaces/IApiUsable.php';
require_once './models/Cliente.php';
require_once './models/Reserva.php';

class CSVController
{
    public function DescargarEntidad($request, $response, $args)
    {
        try {
            $filename = '';
            $queryParams = $request->getQueryParams();
            $entidad = isset($queryParams['entidad'])
                ? $queryParams['entidad']
                : false;

            if (!$entidad) {
                throw new Exception('Para descargar una entidad debe especificarla como parametro.');
            }

            switch ($entidad) {
                case 'clientes':
                    $filename = 'clientes';
                    $bdClientes = Cliente::obtenerTodosCSV();
                    Cliente::DescargarClientesCSV($bdClientes);
                    break;
                case 'reservas':
                    $filename = 'reservas';
                    $bdReservas = Reserva::obtenerTodosCSV();
                    Reserva::DescargarReservasCSV($bdReservas);
                    break;
                default:
                    throw new Exception('Entidad no contemplada.');
            }

            $response = new \Slim\Psr7\Response();
            return $response->withHeader('Content-Type', 'text/csv')->withHeader('Content-Disposition', "attachment; filename=$filename.csv");
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
                    Cliente::CargarUsuariosCSV($archivo->getStream()->getMetadata('uri'));
                    break;
                case 'reservas':
                    $dir = 'reservas';
                    Reserva::CargarReservasCSV($archivo->getStream()->getMetadata('uri'));
                    break;
                default:
                    throw new Exception('Entidad no contemplada.');
            }

            $payload = json_encode(["mensaje" => "Se cargo correctamente la entidad $dir desde el csv en la base de datos"]);
        } catch (Exception $err) {
            $payload = json_encode(['error' => $err->getMessage()]);
        } finally {
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

}

?>