
<?php

class Pedido
{

    function __construct(){

        require_once __DIR__.'/conexion.php';
        $cid = new Conexion();
        $this->cid_central = $cid->conectar('central');

    } 

    public function traerPedidos () {
        
        $sql = "SELECT A.TALON_PED, A.NRO_PEDIDO, A.COD_CLIENT, C.DESC_SUCURSAL, CAST(CANT_PEDID AS FLOAT) CANT_PEDID, A.N_REMITO  FROM GVA21 A
                INNER JOIN (SELECT TALON_PED, NRO_PEDIDO, SUM(CANT_PEDID) CANT_PEDID FROM GVA03 GROUP BY TALON_PED, NRO_PEDIDO) B 
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

}