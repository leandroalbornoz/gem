<?php


$connectionInfo = array( "Database"=>"dbo.pregase" );

$link = sqlsrv_connect("DGE04", $connectionInfo)or die("Error de conexion");

$datosQuery = sqlsrv_query($link, "select EscId, TurCod, DivAniCod, DivNro from CursosyDivisiones where EscId = '1004';")or die("error en query");

while($datos = sqlsrv_fetch_array($datosQuery)){
    echo " ".$datos['EscId']." - ".$datos['TurCod']." - ".$datos['DivAniCod']." - ".$datos['DivNro']." - </br>";
}









sqlsrv_close ($link);