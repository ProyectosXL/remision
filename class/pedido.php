
<?php

class Pedido
{

    function __construct(){

        require_once __DIR__.'/conexion.php';
        $cid = new Conexion();
        $this->cid_central = $cid->conectar('central');

    } 

    public function traerPedidos () {
        
        $sql = "SELECT A.TALON_PED, A.NRO_PEDIDO, A.COD_CLIENT, C.DESC_SUCURSAL, CAST(CANT_PEDID AS FLOAT) CANT_PEDID, A.N_REMITO, NRO_SUCURSAL  FROM GVA21 A
                INNER JOIN (SELECT TALON_PED, NRO_PEDIDO, SUM(CANT_PEDID) CANT_PEDID FROM GVA03 WHERE PROMOCION != '1'  GROUP BY TALON_PED, NRO_PEDIDO) B 
                        ON A.TALON_PED = B.TALON_PED AND A.NRO_PEDIDO = B.NRO_PEDIDO
                INNER JOIN LAKERBIS.LOCALES_LAKERS.DBO.SUCURSALES_LAKERS C ON A.COD_CLIENT = C.COD_CLIENT COLLATE Latin1_General_BIN
                WHERE FECHA_PEDI >= GETDATE()-60 AND A.COD_CLIENT LIKE '[GFM]%' AND A.TALON_PED = '1'";

        $stmt = sqlsrv_query( $this->cid_central, $sql );

        try{

            $rows = array();
    
            while( $v = sqlsrv_fetch_array( $stmt) ) {
                $rows[] = $v;
            }
    
            return $rows;

        } catch (\Throwable $th){
            print_r($th);
        }     

    }

    public function ejecutarRemisionMasiva($data){

        $nroPedido = strlen($data['NRO_PEDIDO']) == 13 ? ' ' . $data['NRO_PEDIDO'] : $data['NRO_PEDIDO'];
        
        $codDeposito = $data['NRO_SUCURSAL'];
        $codCliente = $data['COD_CLIENT'];
    
        $sql = '';
        $sql = "EXEC FU_REMISION_MASIVA '$nroPedido', '$codDeposito', '$codCliente'";

        try{
            $stmt = sqlsrv_query( $this->cid_central, $sql );
            if($stmt === false) {
                throw new Exception("Error al ejecutar la consulta: " . sqlsrv_errors());
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
        

    }
    
    public function insertarHistoricoPedidosDet($idHistorico, $nroPedido, $codCliente, $nroSucurs, $estado){

        $sql = "INSERT INTO FU_REMISION_HISTORICO_DET (ID_TAREA, NRO_PEDIDO, COD_CLIENT, NRO_SUCURS, FINALIZACION) 
                VALUES ($idHistorico, '$nroPedido', '$codCliente', $nroSucurs, getdate())";

        try{
            $stmt = sqlsrv_query( $this->cid_central, $sql );

            if($stmt === false) {
                throw new Exception("Error al ejecutar la consulta: " . sqlsrv_errors());
            }

            return true;
        } catch (Exception $e) {
            return false;
        }

    }
}