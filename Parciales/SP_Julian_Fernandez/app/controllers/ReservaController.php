<?php

require_once './interfaces/IApiUsable.php';
require_once './models/Reserva.php';
require_once './models/Cliente.php';

class ReservaController implements IApiUsable
{
    public function TraerTodos($request, $response, $args)
    {
        $lista = Reserva::obtenerTodos();
        $payload = json_encode(array("listaReservas" => $lista));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['reserva']; // se busca por ID
        $usuario = Reserva::obtenerReserva($id);

        $payload = json_encode(array("reserva" => $usuario));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function CargarUno($request, $response, $args)
    {
        try {
            $parametros = $request->getParsedBody();
            $archivos = $request->getUploadedFiles();

            $tipoCliente = $parametros['tipo_cliente'];
            $nroCliente = $parametros['nro_cliente'];
            $fechaEntrada = new DateTime(date('d-m-Y',
                strtotime($parametros['fecha_entrada'])));
            $fechaSalida = new DateTime(date('d-m-Y',
                strtotime($parametros['fecha_salida'])));
            $tipoHabitacion = $parametros['tipo_habitacion'];
            $importeTotal = $parametros['importe_total'];
            $fotoReserva = $archivos['foto_reserva'];

            $bdCliente = Cliente::obtenerCliente($nroCliente);
            if (!$bdCliente) {
                throw new Exception("No existe un cliente con id $nroCliente");
            }

            $reserva = new Reserva();
            $reserva->setTipoCliente($tipoCliente);
            $reserva->setNroCliente($nroCliente);
            $reserva->setFechaEntrada($fechaEntrada);
            $reserva->setFechaSalida($fechaSalida);
            $reserva->setTipoHabitacion($tipoHabitacion);
            $reserva->setImporteTotal($importeTotal);
            $reserva->setModalidadPago($bdCliente->{'modalidad_pago'});
            $nuevoId = $reserva->crearReserva();
            $reserva->setId($nuevoId);
            $nombreFoto = $reserva->EstablecerFotoReserva($fotoReserva, $nuevoId);
            $reserva->setFotoReserva($nombreFoto);

            $payload = json_encode(array("mensaje" => "Reserva $nuevoId creada con exito"));
        } catch (Exception $err) {
            $payload = json_encode(array("error" => $err->getMessage()));
        } finally {
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public function ModificarUno($request, $response, $args)
    {
        try{
            $parametros = $request->getParsedBody();

            $id = $args['reserva'];
            $tipoCliente = $parametros['tipo_cliente'];
            $nroCliente = $parametros['nro_cliente'];
            $fechaEntrada = new DateTime(date('d-m-Y',
                strtotime($parametros['fecha_entrada'])));
            $fechaSalida = new DateTime(date('d-m-Y',
                strtotime($parametros['fecha_salida'])));
            $tipoHabitacion = $parametros['tipo_habitacion'];
            $importeTotal = $parametros['importe_total'];
            $modalidadPago = $parametros['modalidad_pago'];
            $estado = $parametros['estado'];

            $bdCliente = Cliente::obtenerCliente($nroCliente);
            if (!$bdCliente) {
                throw new Exception("El cliente $nroCliente no existe");
            }
            $bdReserva = Reserva::obtenerReserva($id);
            if (!$bdReserva) {
                throw new Exception("La reserva $id no existe");
            }

            $reserva = new Reserva();
            $reserva->setId($id);
            $reserva->setTipoCliente($tipoCliente);
            $reserva->setNroCliente($nroCliente);
            $reserva->setFechaEntrada($fechaEntrada);
            $reserva->setFechaSalida($fechaSalida);
            $reserva->setTipoHabitacion($tipoHabitacion);
            $reserva->setImporteTotal($importeTotal);
            $reserva->setModalidadPago($modalidadPago);
            $reserva->setEstado($estado);

            $reserva->modificarReserva();

            $payload = json_encode(array("mensaje" => "Reserva $id modificada con exito"));
        } catch (Exception $err) {
            $payload = json_encode(array("error" => $err->getMessage()));
        } finally {
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public function BorrarUno($request, $response, $args)
    {
        try {
            $id = $args['reserva'];
            $bdReserva = Reserva::obtenerReserva($id);
            if (!$bdReserva){
                throw new Exception("La reserva $id no existe");
            }

            Reserva::borrarReserva($id);
            $payload = json_encode(array("mensaje" => "Reserva borrada con exito"));
        } catch (Exception $err) {
            $payload = json_encode(array("error" => $err->getMessage()));
        } finally {
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

}

?>