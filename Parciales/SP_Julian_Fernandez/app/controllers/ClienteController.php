<?php

require_once './interfaces/IApiUsable.php';
require_once './models/Cliente.php';

class ClienteController implements IApiUsable
{
    public function TraerTodos($request, $response, $args)
    {
        $lista = Cliente::obtenerTodos();
        $payload = json_encode(array("listaClientes" => $lista));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        try {
            $queryParams = $request->getQueryParams();
            $tipoCliente = isset($queryParams['tipo_cliente'])
                ? $queryParams['tipo_cliente']
                : false;
            $id = $args['cliente']; // se busca por ID
            
            if (!$tipoCliente){
                throw new Exception('Para obtener un usuario debe especificar su tipo');
            }
            $bdClientes = Cliente::obtenerTodos();

            $usuario = ClienteController::BuscarClientePorIdYTipo($bdClientes, $id, $tipoCliente);
            $payload = json_encode([
                'cliente' => $usuario->{'id'},
                'pais' => $usuario->{'pais'},
                'ciudad' => $usuario->{'ciudad'},
                'telefono' => $usuario->{'telefono'}
            ]);

        } catch (Exception $err) {
            $payload = json_encode(array("error" => $err->getMessage()));
            $response = $response->withStatus(500);
        } finally {
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public function CargarUno($request, $response, $args)
    {
        try {
            $bdUltimo = Cliente::obtenerUltimoCliente();
            if($bdUltimo && ($bdUltimo->{'id'} + 1) > 999999){
                throw new Exception("Se supero el limite de clientes permitidos a crear.");
            }
            $bdClientes = Cliente::obtenerTodos();

            $actualizado = false;
            $parametros = $request->getParsedBody();
            $archivos = $request->getUploadedFiles();

            $usuario = $parametros['usuario'];
            $clave = $parametros['clave'];
            $nombre = $parametros['nombre'];
            $apellido = $parametros['apellido'];
            $email = $parametros['email'];
            $tipoDoc = $parametros['tipo_doc'];
            $nroDoc = $parametros['nro_doc'];
            $tipoCliente = $parametros['tipo_cliente'];
            $pais = $parametros['pais'];
            $ciudad = $parametros['ciudad'];
            $telefono = $parametros['telefono'];
            $modalidadPago = $parametros['modalidad_pago'] ?? 'Efectivo';
            $fotoUsuario = $archivos['foto_usuario'];

            $bdClienteExistente = Cliente::obtenerClienteExistente($nroDoc);
            if ($bdClienteExistente) {
                throw new Exception("Ya existe un cliente con nro. de documento: $nroDoc");
            }

            $cliente = new Cliente();
            $cliente->setUsuario($usuario);
            $cliente->setClave($clave);
            $cliente->setNombre($nombre);
            $cliente->setApellido($apellido);
            $cliente->setEmail($email);
            $cliente->setTipoDoc($tipoDoc);
            $cliente->setNroDoc($nroDoc);
            $cliente->setTipoCliente($tipoCliente);
            $cliente->setPais($pais);
            $cliente->setCiudad($ciudad);
            $cliente->setTelefono($telefono);
            $cliente->setModalidadPago($modalidadPago);

            // Si el nombre y tipo ya existen -> Actualizar informacion
            foreach($bdClientes as $bdCliente){
                if ($bdCliente->{'nombre'} == $cliente->getNombre() && $bdCliente->{'tipo_cliente'} == $cliente->getTipoCliente()){
                    $bdId = $bdCliente->{'id'};
                    $cliente->setId($bdId);
                    $cliente->modificarCliente();
                    $payload = json_encode([
                        "id" => $bdId,
                        "mensaje" => "Cliente actualizado con exito"
                    ]);
                    $actualizado = true;
                }
            }
            if (!$actualizado){
                $nuevoId = $cliente->crearCliente();
                $cliente->setId($nuevoId);
                $nombreFoto = $cliente->EstablecerFotoCliente($fotoUsuario, $nuevoId);
                $cliente->setFotoUsuario($nombreFoto);
                $payload = json_encode([
                    "id" => $nuevoId,
                    "mensaje" => "Cliente creado con exito"
                ]);
            }
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
            $bdClientes = Cliente::obtenerTodos();

            $id = $args['cliente'];
            $usuario = $parametros['usuario'];
            $clave = $parametros['clave'];
            $nombre = $parametros['nombre'];
            $apellido = $parametros['apellido'];
            $email = $parametros['email'];
            $tipoDoc = $parametros['tipo_doc'];
            $nroDoc = $parametros['nro_doc'];
            $tipoCliente = $parametros['tipo_cliente'];
            $pais = $parametros['pais'];
            $ciudad = $parametros['ciudad'];
            $telefono = $parametros['telefono'];
            $modalidadPago = $parametros['modalidad_pago'] ?? 'Efectivo';

            $bdCliente = Cliente::obtenerClientePorId($id);
            if (!$bdCliente) {
                throw new Exception("El cliente $id no existe");
            }
            ClienteController::BuscarClientePorIdYTipo($bdClientes, $id, $tipoCliente);

            $cliente = new Cliente();
            $cliente->setId($id);
            $cliente->setUsuario($usuario);
            $cliente->setClave($clave);
            $cliente->setNombre($nombre);
            $cliente->setApellido($apellido);
            $cliente->setEmail($email);
            $cliente->setTipoDoc($tipoDoc);
            $cliente->setNroDoc($nroDoc);
            $cliente->setTipoCliente($tipoCliente);
            $cliente->setPais($pais);
            $cliente->setCiudad($ciudad);
            $cliente->setTelefono($telefono);
            $cliente->setModalidadPago($modalidadPago);
            $cliente->modificarCliente();
            $payload = json_encode([
                "id" => $id,
                "mensaje" => "Cliente modificado con exito"
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
            $id = $args['cliente'];
            $path = './images/ImagenesBackupClientes/2023/';
            $pathOrigen = './images/ImagenesDeClientes/2023/';
            $parametros = $request->getParsedBody();
            $nroDoc = $parametros['nro_doc'];
            $tipoCliente = $parametros['tipo_cliente'];

            $bdClientes = Cliente::obtenerTodos();

            // 1. Buscar si cliente existe por nro_doc y tipo_cliente
            $bdCliente = ClienteController::BuscarClientePorNroDocYTipo($bdClientes, $nroDoc, $tipoCliente);

            // 2. Borrar las reservas del cliente
            $reservas = Reserva::obtenerReservaPersonalizado("SELECT * 
                FROM reservas
                WHERE nro_cliente = $id");
            if ($reservas){
                foreach ($reservas as $reserva) {
                    Reserva::borrarReserva($reserva->{'id'});
                }
            }

            // 3. Mover la imagen perteneciente al usuario
            Cliente::moverFoto($path, $pathOrigen, $bdCliente->{'foto_usuario'});
            

            // 4. Borrar el usuario
            Cliente::borrarUsuario($id);
            $payload = json_encode([
                "id" => $id,
                "mensaje" => "Usuario borrado con exito",
                "reservas_eliminadas" => count($reservas)
            ]);
        } catch (Exception $err) {
            $payload = json_encode(array("error" => $err->getMessage()));
            $response = $response->withStatus(500);
        } finally {
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public static function BuscarClientePorIdYTipo($bdClientes, $id, $tipoCliente){
        $arrBool = [];
        foreach($bdClientes as $bdCliente){
            if ($bdCliente->{'id'} == $id && strtoupper($bdCliente->{'tipo_cliente'}) == strtoupper($tipoCliente)){
                return $bdCliente;
            } else if ($bdCliente->{'id'} == $id && !(strtoupper($bdCliente->{'tipo_cliente'}) == strtoupper($tipoCliente))){
                throw new Exception('Tipo de cliente incorrecto');
            } else {
                array_push($arrBool, $bdCliente);
            }
        }
        if (count($arrBool) === count($bdClientes)) {
            throw new Exception("No existe un cliente con esa combinacion: Tipo '$tipoCliente', ID Cliente: '$id'");
        }        
    }

    public static function BuscarClientePorNroDocYTipo($bdClientes, $nroDoc, $tipoCliente){
        $arrBool = [];
        foreach($bdClientes as $bdCliente){
            if ($bdCliente->{'nro_doc'} == $nroDoc && strtoupper($bdCliente->{'tipo_cliente'}) == strtoupper($tipoCliente)){
                return $bdCliente;
            } else if ($bdCliente->{'nro_doc'} == $nroDoc && !(strtoupper($bdCliente->{'tipo_cliente'}) == strtoupper($tipoCliente))){
                throw new Exception('Tipo de cliente incorrecto');
            } else {
                array_push($arrBool, $bdCliente);
            }
        }
        if (count($arrBool) === count($bdClientes)) {
            throw new Exception("No existe un cliente con esa combinacion: Tipo '$tipoCliente', Documento: '$nroDoc'");
        }        
    }
}

?>