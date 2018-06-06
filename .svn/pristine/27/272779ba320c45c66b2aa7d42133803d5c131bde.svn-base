<?php
// Test CVS

require_once 'Excel/reader.php';


// ExcelFile($filename, $encoding);
$data = new Spreadsheet_Excel_Reader();

$data2 = new Spreadsheet_Excel_Reader();

// Set output Encoding.
$data->setOutputEncoding('CP1251');
$data2->setOutputEncoding('CP1251');
/***
 * if you want you can change 'iconv' to mb_convert_encoding:
 * $data->setUTFEncoder('mb');
 *
 **/

/***
 * By default rows & cols indeces start with 1
 * For change initial index use:
 * $data->setRowColOffset(0);
 *
 **/


/***
 *  Some function for formatting output.
 * $data->setDefaultFormat('%.2f');
 * setDefaultFormat - set format for columns with unknown formatting
 *
 * $data->setColumnFormat(4, '%.3f');
 * setColumnFormat - set format for column (apply only to number fields)
 *
 **/

$data->read('Libro2.xls');
$data2->read('Libro1.xls');

/*


 $data->sheets[0]['numRows'] - count rows
 $data->sheets[0]['numCols'] - count columns
 $data->sheets[0]['cells'][$i][$j] - data from $i-row $j-column

 $data->sheets[0]['cellsInfo'][$i][$j] - extended info about cell
    
    $data->sheets[0]['cellsInfo'][$i][$j]['type'] = "date" | "number" | "unknown"
        if 'type' == "unknown" - use 'raw' value, because  cell contain value with format '0.00';
    $data->sheets[0]['cellsInfo'][$i][$j]['raw'] = value if cell without format 
    $data->sheets[0]['cellsInfo'][$i][$j]['colspan'] 
    $data->sheets[0]['cellsInfo'][$i][$j]['rowspan'] 
*/

error_reporting(E_ALL ^ E_NOTICE);

/*for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
	for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
		echo "\"" . utf8_encode($data->sheets[0]['cells'][$i][$j]) . "\",";
	}
	echo "\n";
}*/

/* LEYENDO DATOS CRUZADOS  */
$contador = 3;
$c=0;

$datos = array();

for ($i = 3; $i <= $data->sheets[0]['numRows']; $i++) {
    $contador = 3;
    for ($j = 2; $j <= $data->sheets[0]['numCols']; $j++) {

        if ($j == 2) {
            if($data->sheets[0]['cells'][$i][$j] == 1){

                $datos['materia'][$c] = $i-2;
                $datos['carrera'][$c] = $j-1;
                $datos['curso'][$c] = "1";

              //  echo " ".($i-2)." ".($j-1)." 1"."</br>";
                /*mysqli_query($link, "INSERT INTO materia_carrera (materia_id,carrera_id,curso_id)
VALUES ('".($i-2)."', '".($j-1)."', '1')");*/
                $c++;
            }

        } elseif ($j == 3) {
            if ($data->sheets[0]['cells'][$i][$j] == 1) {
                $datos['materia'][$c] = $i-2;
                $datos['carrera'][$c] = $j-2;
                $datos['curso'][$c] = "2";

                //echo " ".($i-2)." ".($j-2)." 2"."</br>";

               /* mysqli_query($link, "INSERT INTO materia_carrera (materia_id,carrera_id,curso_id)
VALUES ('".($i-2)."', '".($j-2)."', '2')");*/
                $c++;
            }
        }elseif ($j>3){

                if ($data->sheets[0]['cells'][$i][$j] == 1) {

                    $datos['materia'][$c] = $i-2;
                    $datos['carrera'][$c] = (intval(($j/3)+0.95));
                    $datos['curso'][$c] = "".$contador;

                    //echo " ".($i-2)." ".(intval(($j/3)+0.95 ))." ".$contador."</br>";

                   /* mysqli_query($link, "INSERT INTO materia_carrera (materia_id,carrera_id,curso_id)
VALUES ('".($i-2)."','".(intval(($j/3)+0.95 ))."','".$contador."')");*/
                    $c++;
                }
            $contador++;

        }
        if($contador>5){
            $contador = 3;
        }

    }
}



/*  LEYENDO DESCRIPCIONES  */



$d=0;

$datos2 = array();

for ($i = 3; $i <= $data2->sheets[0]['numRows']; $i++) {
    for ($j = 2; $j <= $data2->sheets[0]['numCols']; $j++) {
        if ($j == 2) {
            if($data2->sheets[0]['cells'][$i][$j] == 1){

                $datos2['materia'][$d] = $data2->sheets[0]['cells'][$i][1];
                $datos2['carrera'][$d] = $data2->sheets[0]['cells'][1][$j];
                $datos2['curso'][$d] = $data2->sheets[0]['cells'][2][$j];

                $d++;
            }

        } elseif ($j == 3) {
            if ($data2->sheets[0]['cells'][$i][$j] == 1) {
                $datos2['materia'][$d] = $data2->sheets[0]['cells'][$i][1];
                $datos2['carrera'][$d] = $data2->sheets[0]['cells'][1][$j];
                $datos2['curso'][$d] = $data2->sheets[0]['cells'][2][$j];

                $d++;
            }
        }elseif ($j>3){

            if ($data2->sheets[0]['cells'][$i][$j] == 1) {

                $datos2['materia'][$d] = $data2->sheets[0]['cells'][$i][1];
                $datos2['carrera'][$d] = $data2->sheets[0]['cells'][01][$j];
                $datos2['curso'][$d] = $data2->sheets[0]['cells'][2][$j];

                $d++;
            }
        }
    }
}




$link = mysqli_connect("192.168.31.49", "lalbornoz", "comodin1", "cedula");
$cont = 0;

/*      CARGANDO DATOS EN LA DB       */

for($y=0;$y<612;$y++){

    echo " ".$datos['materia'][$y]." ".$datos['carrera'][$y]." ".$datos['curso'][$y]."</br>" ;

  // echo " ".$datos2['materia'][$y]." - ".$datos2['carrera'][$y]." - ".$datos2['curso'][$y]."</br>" ;
    $descripcion = " ".$datos2['materia'][$y]." - ".$datos2['carrera'][$y]." - ".$datos2['curso'][$y];
    $carrera = $datos['carrera'][$y];
    $materia = $datos['materia'][$y];
    $curso = $datos['curso'][$y];
   // echo $descripcion." </br>";

    if (!(mysqli_query($link, "INSERT INTO espacio_curricular (descripcion,carrera_id,materia_id,curso_id)
VALUES ('".$descripcion."','".intval($carrera)."','".intval($materia)."','".intval($curso)."');"))){
        echo "Error: ".mysqli_error($link)."  ".$y." "." </br>";
    }


   /* mysqli_query($link, "INSERT INTO espacio_curricular (descripcion,carrera_id,materia_id,curso_id)
VALUES ('".$descripcion."','".$carrera."','".$materia."','".$curso."');");*/
    $cont++;
}

echo " ".$cont;




/*   mysqli_query($link, "INSERT INTO espacio_curricular (descripcion,carrera_id,materia_id,curso_id)
VALUES ('descrip','1', '2', '3')"); */


/*
        if ($j == 2) {
            if($data->sheets[0]['cells'][$i][$j] == "x"){
                echo " ".($i-2)." ".($j-1)." "."1";
                echo "</br>";
            }

        } elseif ($j == 3) {
                if($data->sheets[0]['cells'][$i][$j] == "x"){
                    echo "   ".($i-2)." ".($j-2)." "."2";
                    echo "</br>";
            }

        } elseif (3 < $j && $j <= 75) {
                if($data->sheets[0]['cells'][$i][$j] == "x"){
                    //+1 por el desplazamiento de la columna que tiene las materias
                    echo "   ".($i-2)." ".((intval($j/3))+1)." ".$contador;
                    echo "</br>";
            }
            $contador =$contador+1;

        }

        if($contador==6){
            $contador = 3;
        }

    }
}

*/


















/*
for ($i = 3; $i <= $data->sheets[0]['numRows']; $i++) {
    $control = 0;
    for ($j = 2; $j <= $data->sheets[0]['numCols']; $j++) {

        if ($j>=3 && $j < 48){
            $control= (intval($j/3))+1;
        } if($j >= 48) {
                    $control = (intval($j / 5)) + 7;
                }
                if($data->sheets[0]['cells'][$i][$j] == "x"){

                    if ($j==2){
                        echo " ".($i-2)." "."1"." 1 ,";
                    }elseif($j==3){
                        echo " ".($i-2)." "."1"." 2 ,";

                    }elseif(3<$j && $j<48){
                        for ($k = 1; $k <= 3; $k++) {
                            echo " ".($i-2)." ".($control)." ".$k." ,";
                        }

                    }elseif($j>=48 && $j<94){
                        for ($x = 1; $x <= 5; $x++) {
                            echo " ".($i-2)." ".($control)." ".$x." ,";
                        }

                    }
                    //echo " ".($i-2).($j-1);
                }
            }

            echo "</br>";*/



/*
for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
if ($data->sheets[0]['cells'][$i][1]) {

	mysqli_query($link, "INSERT INTO materia (descripcion)
VALUES ('".$data->sheets[0]['cells'][$i][1]."')");
}
}*/
/*
for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
	if ($data->sheets[0]['cells'][1][$j] != ""){
		mysqli_query($link, "INSERT INTO carrera (descripcion)
VALUES ('".$data->sheets[0]['cells'][1][$j]."')");
	}
}
*/


//print_r($data);
//print_r($data->formatRecords);
?>
