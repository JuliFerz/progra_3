<?php

require_once './interfaces/IApiUsable.php';
require_once './models/Cliente.php';
// require_once './models/Sector.php';

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
        $id = $args['cliente']; // se busca por ID
        $usuario = Cliente::obtenerCliente($id);

        $payload = json_encode(array("cliente" => $usuario));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function CargarUno($request, $response, $args)
    {
        try {
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

            $bdClienteExistente = Cliente::obtenerClienteExistente($email);
            if ($bdClienteExistente) {
                throw new Exception("Ya existe un cliente con correo $email");
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
            $nuevoId = $cliente->crearCliente();
            $cliente->setId($nuevoId);
            $nombreFoto = $cliente->EstablecerFotoCliente($fotoUsuario, $nuevoId);
            $cliente->setFotoUsuario($nombreFoto);

            $payload = json_encode(array("mensaje" => "Cliente $nuevoId creado con exito"));
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

        //     $bdSector = Sector::obtenerSectorDisponible($idSector);
        //     if (!$bdSector) {
        //         $sectorTodos = Sector::obtenerTodos();
        //         $strDisponibles = '';

        //         foreach ($sectorTodos as $sector){
        //             if ($sector->{'fecha_baja'} != null){
        //                 continue;
        //             }
        //             $idS = $sector->{'id'};
        //             $detalleS = $sector->{'detalle'};
        //             $strDisponibles .= "$detalleS ($idS), ";
        //         }
        //         $strDisponibles = substr($strDisponibles, 0, strlen($strDisponibles) - 2);

        //         throw new Exception("El sector $idSector no esta disponible. Los sectores disponibles son: $strDisponibles.");
        //     }

            $bdCliente = Cliente::obtenerCliente($id);
            if (!$bdCliente) {
                throw new Exception("El cliente $id no existe");
            }

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
            $payload = json_encode(array("mensaje" => "Cliente $id modificado con exito"));
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
            $id = $args['cliente'];
            $bdUser = Cliente::obtenerCliente($id);
            if (!$bdUser){
                throw new Exception("El cliente $id no existe");
            }

            Cliente::borrarUsuario($id);
            $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));
        } catch (Exception $err) {
            $payload = json_encode(array("error" => $err->getMessage()));
        } finally {
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

}

?>