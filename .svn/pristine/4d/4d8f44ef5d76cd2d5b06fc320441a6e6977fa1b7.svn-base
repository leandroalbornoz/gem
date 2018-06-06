<?php

include("Excel/reader.php");


$datos = new Spreadsheet_Excel_Reader();


$datos->read('Libro1.xls');


$celdas = $datos->sheets[0]['cells'];


echo "";


$i = 1;
while($celdas[$i][1]!='') {
echo "";
$i++;
}


echo "<table width='300' align='center'>
<tbody>
<tr>
<td width='150' align='center'>".$celdas[$i][1]."</td>
<td width='150' align='center'>".$celdas[$i][2]."</td>
</tr>
</tbody>
</table>";

?>