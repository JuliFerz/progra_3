<?php

require_once './interfaces/IApiUsable.php';
require_once './models/Reserva.php';
require_once './models/Cliente.php';
require_once './models/Ajuste.php';

class ReservaController implements IApiUsable
{
    public function TraerTodos($request, $response, $args)
    {
        try {
            $queryParams = $request->getQueryParams();
            $tipoCliente = isset($queryParams['filtro']) && $queryParams['filtro'] != null
                ? $queryParams['filtro']
                : false;

            if ($tipoCliente){
                $lista = $this->TraerReservasFiltradas(Reserva::obtenerTodos(), $tipoCliente, $queryParams);
                $payload = json_encode($lista);
            } else {
                $lista = Reserva::obtenerTodos();
                $payload = json_encode(array("listaReservas" => $lista));
            }
        } catch (Exception $err) {
            $payload = json_encode(array("error" => $err->getMessage()));
            $response = $response->withStatus(500);
        } finally {
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
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

            $bdCliente = Cliente::obtenerClientePorId($nroCliente);
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

            $payload = json_encode([
                "id" => $nuevoId,
                "mensaje" => "Reserva creada con exito"
            ]);
        } catch (Exception $err) {
            $payload = json_encode(array("error" => $err->getMessage()));
            $response = $response->withStatus(500);
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

            $bdCliente = Cliente::obtenerClientePorId($nroCliente);
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

            $payload = json_encode([
                "id" => $id,
                "mensaje" => "Reserva modificada con exito"
            ]);
        } catch (Exception $err) {
            $payload = json_encode(array("error" => $err->getMessage()));
            $response = $response->withStatus(500);
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
            $payload = json_encode([
                "id" => $id,
                "mensaje" => "Reserva borrada con exito"
            ]);
        } catch (Exception $err) {
            $payload = json_encode(array("error" => $err->getMessage()));
            $response = $response->withStatus(500);
        } finally {
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public function CancelarUno($request, $response, $args)
    {
        try {
            $id = $args['reserva'];
            $parametros = $request->getParsedBody();
            $nroCliente = $parametros['nro_cliente'];
            $tipoCliente = $parametros['tipo_cliente'];
            
            
            $bdReserva = Reserva::obtenerReserva($id);
            if (!$bdReserva){
                throw new Exception("La reserva $id no existe");
            }
            $bdClientes = Cliente::obtenerTodos();
            ClienteController::BuscarClientePorIdYTipo($bdClientes, $nroCliente, $tipoCliente);
            if ($bdReserva->{'nro_cliente'} != $nroCliente || strtoupper($bdReserva->{'tipo_cliente'}) != strtoupper($tipoCliente)){
                throw new Exception("La reserva $id no le pertenece al usuario $nroCliente (tipo '$tipoCliente')");
            }

            Reserva::borrarReserva($id);
            $payload = json_encode([
                "id" => $id,
                "mensaje" => "Reserva cancelada con exito"
            ]);
        } catch (Exception $err) {
            $payload = json_encode(array("error" => $err->getMessage()));
            $response = $response->withStatus(500);
        } finally {
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public function AjustarUno($request, $response, $args)
    {
        try {
            $id = $args['reserva'];
            $parametros = $request->getParsedBody();
            $importe = $parametros['importe'];
            $motivo = $parametros['motivo'];

            $bdReserva = Reserva::obtenerReserva($id);
            if (!$bdReserva){
                throw new Exception("La reserva $id no existe");
            }

            ReservaController::validarFormatoNumero($importe);

            // Se agrega el ajuste en la tabla
            $ajuste = new Ajuste();
            $ajuste->setIdReserva($id);
            $ajuste->setImportePrevio($bdReserva->{'importe_total'});
            $ajuste->setImporteNuevo($importe);
            $ajuste->setMotivo($motivo);
            $ajuste->crearAjuste();

            // Actualiza la reserva
            $bdReserva->{'importe_total'} = $importe;
            $bdReserva->modificarReserva(true);

            // Reserva::borrarReserva($id);
            $payload = json_encode([
                "id" => $id,
                "mensaje" => "Reserva ajustada con exito"
            ]);
        } catch (Exception $err) {
            $payload = json_encode(array("error" => $err->getMessage()));
            $response = $response->withStatus(500);
        } finally {
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public static function TraerReservasFiltradas($reservas, $filtro, $queryParams){
        switch($filtro){
            case 'total_reservas':
                $res = ReservaController::TotalReservas($reservas, $filtro, $queryParams);
                break;
            case 'por_cliente':
                $res = ReservaController::PorCliente($reservas, $filtro, $queryParams);
                break;
            case 'entre_fechas':
                $res = ReservaController::EntreFechas($reservas, $filtro, $queryParams);
                break;
            case 'por_habitacion':
                $res = ReservaController::PorHabitacion($reservas, $filtro, $queryParams);
                break;
            case 'total_cancelado':
                $res = ReservaController::TotalCancelado($reservas, $filtro, $queryParams);
                break;
            case 'canceladas_por_cliente':
                $res = ReservaController::CanceladasPorCliente($reservas, $filtro, $queryParams);
                break;
            case 'canceladas_entre_fechas':
                $res = ReservaController::CanceladasEntreFechas($reservas, $filtro, $queryParams);
                break;
            case 'canceladas_por_tipo_cliente':
                $res = ReservaController::CanceladasPorTipoCliente($reservas, $filtro, $queryParams);
                break;
            case 'todas_reservas_por_cliente':
                $res = ReservaController::TodasReservasPorCliente($reservas, $filtro, $queryParams);
                break;
            case 'por_modalidad_pago':
                $res = ReservaController::PorModalidadPago($reservas, $filtro, $queryParams);
                break;
            default:
                throw new Exception('No se encontro el listado para retornar');

        }
        return $res;
    }
    
    public static function TotalReservas($reservas, $filtro, $queryParams){
        $tipoHabitacion = isset($queryParams['tipo_habitacion']) 
                && $queryParams['tipo_habitacion'] != null
            ? $queryParams['tipo_habitacion']
            : false;

        $fecha = isset($queryParams['fecha']) && $queryParams['fecha'] != null
            ? $queryParams['fecha']
            : date('Y-m-d', strtotime("- 1 days"));
        
        ReservaController::validarFormatoFecha($fecha);
        if (!$tipoHabitacion){
            throw new Exception('Para filtrar por total reservas, indique el tipo de habitacion.');
        }
        $query = "SELECT SUM(importe_total) as suma FROM reservas
            WHERE fecha_entrada = '$fecha'
                AND tipo_habitacion = '$tipoHabitacion'
                AND fecha_baja IS NULL"; 
        $res = Reserva::obtenerReservaPersonalizado($query);
        $total = $res[0]->{'suma'} ?? 0;

        return ["total_reservas" => $total];
    }

    public static function PorCliente($reservas, $filtro, $queryParams){
        $idCliente = isset($queryParams['nro_cliente']) 
                && $queryParams['nro_cliente'] != null
            ? $queryParams['nro_cliente']
            : false;

        if (!$idCliente){
            throw new Exception('Para filtrar por cliente, debe proveer un nro de cliente.');
        }
        if (!Cliente::obtenerClientePorId($idCliente)) {
            throw new Exception("El cliente $idCliente no existe");
        }
        ReservaController::validarFormatoNumero($idCliente);
        $idCliente = (int) $idCliente;

        $query = "SELECT * 
            FROM reservas
            WHERE nro_cliente = $idCliente
            AND fecha_baja IS NULL";
        $res = Reserva::obtenerReservaPersonalizado($query);

        return ["cliente" => $idCliente, "reservas_filtradas" => $res];
    }

    public static function EntreFechas($reservas, $filtro, $queryParams){
        $fechaUno = isset($queryParams['fecha_uno']) 
                && $queryParams['fecha_uno'] != null
            ? $queryParams['fecha_uno']
            : false;
        $fechaDos = isset($queryParams['fecha_dos']) 
                && $queryParams['fecha_dos'] != null
            ? $queryParams['fecha_dos']
            : false;

        if (!$fechaUno || !$fechaDos){
            throw new Exception('Para filtrar por fechas, debe proveer dos fechas');
        }
        ReservaController::validarFormatoFecha($fechaUno);
        ReservaController::validarFormatoFecha($fechaDos);

        $query = "SELECT * FROM reservas
            WHERE fecha_entrada BETWEEN '$fechaUno' AND '$fechaDos'
            AND fecha_baja IS NULL
            ORDER BY fecha_entrada;";
        $res = Reserva::obtenerReservaPersonalizado($query);

        return [
            "fecha_uno" => $fechaUno,
            "fecha_dos" => $fechaDos, 
            "reservas_filtradas" => $res
        ];
    }

    public static function PorHabitacion($reservas, $filtro, $queryParams){
        $tipoHabitacion = isset($queryParams['tipo_habitacion']) 
                && $queryParams['tipo_habitacion'] != null
            ? $queryParams['tipo_habitacion']
            : false;

        if (!$tipoHabitacion){
            throw new Exception('Para filtrar por habitacion, debe proveer un tipo de habitacion.');
        }

        $query = "SELECT * 
            FROM reservas
            WHERE tipo_habitacion = '$tipoHabitacion'
            AND fecha_baja IS NULL;";
        $res = Reserva::obtenerReservaPersonalizado($query);

        return [
            "tipo_habitacion" => $tipoHabitacion,
            "reservas_filtradas" => $res
        ];
    }

    public static function TotalCancelado($reservas, $filtro, $queryParams){
        $tipo_cliente = isset($queryParams['tipo_cliente']) 
                && $queryParams['tipo_cliente'] != null
            ? $queryParams['tipo_cliente']
            : false;
        ReservaController::validarTipoCliente($tipo_cliente);

        $fecha = isset($queryParams['fecha']) && $queryParams['fecha'] != null
            ? $queryParams['fecha']
            : date('Y-m-d', strtotime("- 1 days"));
        
        ReservaController::validarFormatoFecha($fecha);
        if (!$tipo_cliente){
            throw new Exception('Para filtrar por total reservas, indique el tipo de habitacion.');
        }
        $query = "SELECT SUM(importe_total) AS suma
            FROM reservas
            WHERE fecha_entrada = '$fecha'
                AND tipo_cliente = '$tipo_cliente'
                AND fecha_baja IS NOT NULL"; 
        $res = Reserva::obtenerReservaPersonalizado($query);
        $total = $res[0]->{'suma'} ?? 0;

        return ["total_reservas_canceladas" => $total];
    }

    public static function CanceladasPorCliente($reservas, $filtro, $queryParams){
        $idCliente = isset($queryParams['nro_cliente']) 
                && $queryParams['nro_cliente'] != null
            ? $queryParams['nro_cliente']
            : false;
            
        if (!$idCliente){
            throw new Exception('Para filtrar por cliente, debe proveer un nro de cliente.');
        }
        if (!Cliente::obtenerClientePorId($idCliente)) {
            throw new Exception("El cliente $idCliente no existe");
        }
        ReservaController::validarFormatoNumero($idCliente);
        $idCliente = (int) $idCliente;

        $query = "SELECT * 
            FROM reservas
            WHERE nro_cliente = $idCliente
            AND fecha_baja IS NOT NULL;";
        $res = Reserva::obtenerReservaPersonalizado($query);

        return ["cliente" => $idCliente, "reservas_canceladas" => $res];
    }

    public static function CanceladasEntreFechas($reservas, $filtro, $queryParams){
        $fechaUno = isset($queryParams['fecha_uno']) 
                && $queryParams['fecha_uno'] != null
            ? $queryParams['fecha_uno']
            : false;
        $fechaDos = isset($queryParams['fecha_dos']) 
                && $queryParams['fecha_dos'] != null
            ? $queryParams['fecha_dos']
            : false;

        if (!$fechaUno || !$fechaDos){
            throw new Exception('Para filtrar por fechas, debe proveer dos fechas');
        }
        ReservaController::validarFormatoFecha($fechaUno);
        ReservaController::validarFormatoFecha($fechaDos);

        $query = "SELECT * FROM reservas
            WHERE fecha_entrada BETWEEN '$fechaUno' AND '$fechaDos'
            AND fecha_baja IS NOT NULL
            ORDER BY fecha_entrada;";
        $res = Reserva::obtenerReservaPersonalizado($query);

        return [
            "fecha_uno" => $fechaUno,
            "fecha_dos" => $fechaDos, 
            "reservas_canceladas" => $res
        ];
    }

    public static function CanceladasPorTipoCliente($reservas, $filtro, $queryParams){
        $tipo_cliente = isset($queryParams['tipo_cliente']) 
                && $queryParams['tipo_cliente'] != null
            ? $queryParams['tipo_cliente']
            : false;
        ReservaController::validarTipoCliente($tipo_cliente);
        
        if (!$tipo_cliente){
            throw new Exception('Para filtrar por total reservas, indique el tipo de cliente.');
        }
        $query = "SELECT *
            FROM reservas
            WHERE tipo_cliente = '$tipo_cliente'
                AND fecha_baja IS NOT NULL;"; 
        $res = Reserva::obtenerReservaPersonalizado($query);

        return ["reservas_canceladas" => $res];
    }

    public static function TodasReservasPorCliente($reservas, $filtro, $queryParams){
        $idCliente = isset($queryParams['nro_cliente']) 
                && $queryParams['nro_cliente'] != null
            ? $queryParams['nro_cliente']
            : false;
            
        if (!$idCliente){
            throw new Exception('Para filtrar por cliente, debe proveer un nro de cliente.');
        }
        if (!Cliente::obtenerClientePorId($idCliente)) {
            throw new Exception("El cliente $idCliente no existe");
        }
        ReservaController::validarFormatoNumero($idCliente);
        $idCliente = (int) $idCliente;

        $query = "SELECT *
            FROM reservas
            WHERE nro_cliente = $idCliente;";
        $res = Reserva::obtenerReservaPersonalizado($query);

        return ["cliente" => $idCliente, "total_reservas_canceladas" => $res];
    }

    public static function PorModalidadPago($reservas, $filtro, $queryParams){
        $modalidad = isset($queryParams['modalidad_pago']) 
                && $queryParams['modalidad_pago'] != null
            ? $queryParams['modalidad_pago']
            : false;

        if (!$modalidad){
            throw new Exception('Para filtrar por modalidad de pago, debe proveer una.');
        }

        $query = "SELECT *
            FROM reservas
            WHERE modalidad_pago = '$modalidad';";
        $res = Reserva::obtenerReservaPersonalizado($query);

        return ["modalidad_pago" => $modalidad, "reservas" => $res];
    }

    // Auxiliares
    public static function validarFormatoNumero($strNumber) {
        if (!preg_match('/^[0-9]+$/', $strNumber)) {
            throw new Exception('Se encontro un caracter no numerico');
        }
    }
    public static function validarFormatoFecha($strDate) {
        if (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $strDate)) {
            throw new Exception("Los formatos fecha no coinciden. Formato valido: 'yyyy-dd-mm'");
        }
    }
    public static function validarTipoCliente($tipoCliente){
        if (strtoupper($tipoCliente) != 'INDI' && strtoupper($tipoCliente) != 'CORPO') {
            throw new Exception('El cliente debe ser de tipo \'individual\' (INDI) o \'corporativo\' (CORPO)');
        }
    }
}

?>