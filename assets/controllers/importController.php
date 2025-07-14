
<?php
// Definir la ruta base (ajustada para la estructura correcta)
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(dirname(dirname(__FILE__))));
}

require_once BASE_PATH . '/class/pedido.php';
require_once BASE_PATH . '/class/classEnv.php';

class ImportController {
    private $pedido;
    private $apiUrl;
    
    public function __construct() {
        $this->pedido = new Pedido();
        $vars = new DotEnv(__DIR__ . '/../../.env');
        $this->envVars = $vars->listVars();
        $this->apiUrl =  $this->envVars['API_URL'];
    
    }
    
    public function handleRequest() {
        header('Content-Type: application/json');
        
        // Obtener el contenido JSON de la solicitud
        $jsonInput = file_get_contents('php://input');
        $data = json_decode($jsonInput, true);
        
        if (!$data) {
            $data = $_POST;
        }
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
                case 'getTareas':
                    $this->getTareas($data['data']);
                    break;
                case 'getDetalleTarea':
                    $this->obtenerDetalleTarea($data['idTarea']);
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
                $key = $pedido['TALON_PED'] . '-' . trim($pedido['NRO_PEDIDO']);
                $pedidosMap[$key] = $pedido;
            }
            
            // Validar cada pedido del Excel
            $resultados = array();
            foreach ($data as $row) {
          
                $key = $row['talonario'] . '-' . trim($row['numeroPedido']);

                if (isset($pedidosMap[$key])) {
                    // El pedido existe
                    $pedidoInfo = $pedidosMap[$key];
                    $resultados[] = array(
                        'TALON_PED' => $pedidoInfo['TALON_PED'],
                        'NRO_PEDIDO' => $pedidoInfo['NRO_PEDIDO'],
                        'COD_CLIENT' => $pedidoInfo['COD_CLIENT'],
                        'DESC_SUCURSAL' => $pedidoInfo['DESC_SUCURSAL'],
                        'CANT_PEDID' => $pedidoInfo['CANT_PEDID'],
                        'exists' => true,
                        'NRO_SUCURSAL' => $pedidoInfo['NRO_SUCURSAL']
                    );
                } else {
                    // El pedido no existe
                    $resultados[] = array(
                        'TALON_PED' => $row['talonario'],
                        'NRO_PEDIDO' => $row['numeroPedido'],
                        'COD_CLIENT' => 'N/A',
                        'DESC_SUCURSAL' => 'No encontrado',
                        'CANT_PEDID' => 0,
                        'exists' => false,
                        'NRO_SUCURSAL' => 0
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
            
            $cantidadPedidos = count($pedidosValidos);
            $tipo = 1;
            $estado = 1;
            
            $idTareaEnc = $this->registrarPedido($cantidadPedidos, $tipo, $estado);

            if($idTareaEnc == 0) {
                throw new Exception('Error al registrar el pedido');
            }

            foreach ($pedidosValidos as $pedido) {
                $resultRemision = $this->pedido->ejecutarRemisionMasiva($pedido);

                if(!$resultRemision) {
                    throw new Exception('Error al procesar el pedido: ' . $pedido['NRO_PEDIDO']);
                }

                $this->insertarHistoricoPedidosDet($idTareaEnc, $pedido);

            }

            $this->sendResponse(true, 'Pedidos procesados correctamente', [
                'total_procesados' => count($pedidosValidos),
                'idTareaEnc' => $idTareaEnc
            ]);
            
        } catch (Exception $e) {
            $this->sendResponse(false, 'Error al procesar remisión: ' . $e->getMessage());
        }
    }
    
    private function programarRemision($data, $scheduledDateTime) {
        try {
            $fechaProgramada = new DateTime($scheduledDateTime);
            $ahora = new DateTime();
     
            if ($fechaProgramada > $ahora) {
                throw new Exception('La fechaa programada debe ser posterior a la actual');
            }
            
            $pedidosValidos = array_filter($data, function($pedido) {
                return $pedido['exists'] === true;
            });
            
            if (empty($pedidosValidos)) {
                throw new Exception('No hay pedidos válidos para programar');
            }
            
            $idProgramacion = uniqid('PROG_');
            foreach ($pedidosValidos as $pedido) {
                $this->registrarPedidoProgramado($idProgramacion, $pedido, $scheduledDateTime);
            }

            $idTareaEnc = $this->registrarPedido(count($pedidosValidos), 2, 2, $scheduledDateTime);

            if($idTareaEnc == 0) {
                throw new Exception('Error al registrar el pedido');
            }

            $this->insertarHistoricoPedidosDet($idTareaEnc, $pedidosValidos);

            
            $this->sendResponse(true, 'Remisión programada correctamente', [
                'id_programacion' => $idProgramacion,
                'fecha_programada' => $scheduledDateTime,
                'total_pedidos' => count($pedidosValidos),
                'idTareaEnc' => $idTareaEnc
            ]);
            
        } catch (Exception $e) {
            $this->sendResponse(false, 'Error al programar remisión: ' . $e->getMessage());
        }
    }

    private function insertarHistoricoPedidosDet($idTareaEnc, $pedido) {
        $nroPedido = $pedido['NRO_PEDIDO'];
        $codDeposito = '2'; 
        $codCliente = $pedido['COD_CLIENT'];
        $estado = 1; 
        $result = $this->pedido->insertarHistoricoPedidosDet($idTareaEnc, $nroPedido, $codCliente, $codDeposito, $estado);
        return $result  ;
    }
    
    private function registrarPedidoProgramado($idProgramacion, $pedido, $fechaProgramada) {
        $url = $this->apiUrl . '/remisionMasiva/programarTarea';
        
        $data = [
            "idProgramacion" => $idProgramacion,
            "pedido" => $pedido,
            "fechaProgramada" => $fechaProgramada
        ];
    
        $jsonData = json_encode($data);
    
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
        curl_close($ch);
    
        if ($httpCode == 200) {
            return json_decode($response, true);
        } else {
            return [
                "success" => false,
                "message" => "Error al programar el pedido en la API de Node.js"
            ];
        }
    }
    
    
    private function sendResponse($success, $message, $data = null) {
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }

    private function getTareas($data){
        
        $url = $this->apiUrl . '/remisionMasiva/tareas';

        $jsonData = json_encode($data);
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
        curl_close($ch);
    
        if ($httpCode == 200) {
            echo json_encode($response);
        } else {
            return [
                "success" => false,
                "message" => "Error al programar el pedido en la API de Node.js"
            ];
        }
    }

    private function registrarPedido($cantidadPedidos, $tipo, $estado, $fecha = null) {
        $url = $this->apiUrl . '/remisionMasiva/registrar';

        $jsonData = json_encode([
            "cantidadPedidos" => $cantidadPedidos,
            "tipo" => $tipo,
            "estado" => $estado,
            "fecha" => $fecha
        ]);
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
        curl_close($ch);
    
        if ($httpCode == 200) {
            $response = json_decode($response, true);
            if (isset($response['body']) && is_array($response['body'])) {
                return $response['body'][0]['ID'];
            } else {
                return 0;
            }
        } else {
            return [
                "success" => false,
                "message" => "Error al programar el pedido en la API de Node.js"
            ];
        }
    
        return true;
    }

    private function obtenerDetalleTarea($idTarea) {

        $url = $this->apiUrl . '/remisionMasiva/detalleTarea';

        $jsonData = json_encode([
            "idEncabezado" => $idTarea
        ]);
        
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        $res = json_decode($response, true)['body'];
    
        if ($httpCode == 200) {
            echo json_encode($res);
        } else {
            return [
                "success" => false,
                "message" => "Error al obtener el detalle de la tarea en la API de Node.js"
            ];
        }


    }
}

// Iniciar el controlador
$controller = new ImportController();
$controller->handleRequest();