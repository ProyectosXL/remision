
<?php
// Definir la ruta base (ajustada para la estructura correcta)
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(dirname(dirname(__FILE__))));
}

require_once BASE_PATH . '/class/pedido.php';

class ImportController {
    private $pedido;
    
    public function __construct() {
        $this->pedido = new Pedido();
    }
    
    public function handleRequest() {
        header('Content-Type: application/json');
        
        // Obtener el contenido JSON de la solicitud
        $jsonInput = file_get_contents('php://input');
        $data = json_decode($jsonInput, true);
        
        if (!$data) {
            $this->sendResponse(false, 'Datos inválidos');
            return;
        }
        
        try {
            switch ($data['action']) {
                case 'validar':
                    $this->validarPedidos($data['data']);
                    break;
                case 'remitir':
                    $this->procesarRemision($data['data']);
                    break;
                case 'programar':
                    $this->programarRemision($data['data'], $data['scheduledDateTime']);
                    break;
                default:
                    $this->sendResponse(false, 'Acción no válida');
            }
        } catch (Exception $e) {
            $this->sendResponse(false, 'Error en el servidor: ' . $e->getMessage());
        }
    }
    
    private function validarPedidos($data) {
        try {
            // Obtener todos los pedidos de la base de datos
            $pedidosDB = $this->pedido->traerPedidos();
            
            // Crear un array asociativo para búsqueda rápida
            $pedidosMap = array();
            foreach ($pedidosDB as $pedido) {
                $key = $pedido['TALON_PED'] . '-' . $pedido['NRO_PEDIDO'];
                $pedidosMap[$key] = $pedido;
            }
            
            // Validar cada pedido del Excel
            $resultados = array();
            foreach ($data as $row) {
                $key = $row['talonario'] . '-' . $row['numeroPedido'];
                
                if (isset($pedidosMap[$key])) {
                    // El pedido existe
                    $pedidoInfo = $pedidosMap[$key];
                    $resultados[] = array(
                        'TALON_PED' => $pedidoInfo['TALON_PED'],
                        'NRO_PEDIDO' => $pedidoInfo['NRO_PEDIDO'],
                        'COD_CLIENT' => $pedidoInfo['COD_CLIENT'],
                        'DESC_SUCURSAL' => $pedidoInfo['DESC_SUCURSAL'],
                        'CANT_PEDID' => $pedidoInfo['CANT_PEDID'],
                        'exists' => true
                    );
                } else {
                    // El pedido no existe
                    $resultados[] = array(
                        'TALON_PED' => $row['talonario'],
                        'NRO_PEDIDO' => $row['numeroPedido'],
                        'COD_CLIENT' => 'N/A',
                        'DESC_SUCURSAL' => 'No encontrado',
                        'CANT_PEDID' => 0,
                        'exists' => false
                    );
                }
            }
            
            $this->sendResponse(true, 'Validación completada', $resultados);
            
        } catch (Exception $e) {
            $this->sendResponse(false, 'Error en la validación: ' . $e->getMessage());
        }
    }
    
    private function procesarRemision($data) {
        try {
            // Filtrar solo los pedidos válidos
            $pedidosValidos = array_filter($data, function($pedido) {
                return $pedido['exists'] === true;
            });
            
            if (empty($pedidosValidos)) {
                throw new Exception('No hay pedidos válidos para procesar');
            }
            
            // Aquí podrías agregar la lógica para procesar los pedidos en el CRM
            // Por ahora solo registramos los pedidos
            
            foreach ($pedidosValidos as $pedido) {
                $this->registrarPedidoProcesado($pedido);
            }
            
            $this->sendResponse(true, 'Pedidos procesados correctamente', [
                'total_procesados' => count($pedidosValidos)
            ]);
            
        } catch (Exception $e) {
            $this->sendResponse(false, 'Error al procesar remisión: ' . $e->getMessage());
        }
    }
    
    private function programarRemision($data, $scheduledDateTime) {
        try {
            // Validar la fecha programada
            $fechaProgramada = new DateTime($scheduledDateTime);
            $ahora = new DateTime();
            
            if ($fechaProgramada < $ahora) {
                throw new Exception('La fecha programada debe ser posterior a la actual');
            }
            
            // Filtrar solo los pedidos válidos
            $pedidosValidos = array_filter($data, function($pedido) {
                return $pedido['exists'] === true;
            });
            
            if (empty($pedidosValidos)) {
                throw new Exception('No hay pedidos válidos para programar');
            }
            
            // Aquí podrías agregar la lógica para programar los pedidos
            // Por ahora solo registramos la programación
            
            $idProgramacion = uniqid('PROG_');
            foreach ($pedidosValidos as $pedido) {
                $this->registrarPedidoProgramado($idProgramacion, $pedido, $scheduledDateTime);
            }
            
            $this->sendResponse(true, 'Remisión programada correctamente', [
                'id_programacion' => $idProgramacion,
                'fecha_programada' => $scheduledDateTime,
                'total_pedidos' => count($pedidosValidos)
            ]);
            
        } catch (Exception $e) {
            $this->sendResponse(false, 'Error al programar remisión: ' . $e->getMessage());
        }
    }
    
    private function registrarPedidoProcesado($pedido) {
        // Aquí implementarías la lógica para registrar el pedido procesado
        // Por ejemplo, guardar en una tabla de la base de datos
        return true;
    }
    
    private function registrarPedidoProgramado($idProgramacion, $pedido, $fechaProgramada) {
        // Aquí implementarías la lógica para registrar el pedido programado
        // Por ejemplo, guardar en una tabla de programaciones
        return true;
    }
    
    private function sendResponse($success, $message, $data = null) {
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }
}

// Iniciar el controlador
$controller = new ImportController();
$controller->handleRequest();