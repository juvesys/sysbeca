<?php
/**
 * Created by PhpStorm.
 * User: Toshiba
 * Date: 27/03/2018
 * Time: 23:34
 */
require_once("../models/alumnos_model.php");
require_once("../db/conectar.php");
require_once ( 'simplexlsx.class.php');


//Se instancia el objeto alumnos
$alumnos=new alumnos_model();
$conexion = Conectar::conexion();

$conection = new conectar();
$db = $conection->getdb();

if (isset($_GET["method"])){
    switch ($_GET["method"])
    {
        case "getbecasbyalum":


            $becados=$alumnos->get_becas_by_alumnos();
            $i=0;
            require_once("../views/create_pdf.phtml");





            break;
    }

}

if (isset($_POST["method"])){

    switch ($_POST["method"])
    {
        case "getalumnosbyselect":
            $id = $_POST["idalumno"];

            $datos=$alumnos->get_alumnos_select($id);

            require_once("../views/alumnnos_from_select.phtml");
            break;
        case "clear":



            $sql = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA LIKE '$db'";
            $result = $conexion->query($sql);
            $tables = $result->fetch_all(MYSQLI_ASSOC);
            foreach($tables as $table)
            {
                $sql = "TRUNCATE TABLE `".$table['TABLE_NAME']."`";
                $result = $conexion->query($sql);
            }
            echo 'Se han vaciado las tablas';


            break;
        case "getbecado":
            $base_url='http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI'].'?').'/';
            $id = $_POST["idalumno"];
            $becados = $alumnos->get_becados_alumnos($id);
            require_once("../views/becas_from_alumnos.phtml");
            break;
        case "addbecado":
            $base_url='http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI'].'?').'/';
            $id = $_POST["idalumno"];

            $becados = $alumnos->add_becados_alumnos($id);
            echo $id;
            //require_once("../views/becas_from_alumnos.phtml");
            break;
        case "updatebecado":
            $id = $_POST["idalumno"];
            $tipobeca = $_POST["tipobeca"];
            $periodoescolar = $_POST["periodoescolar"];

            $updatebecados = $alumnos->update_becados_alumnos($id,$tipobeca,$periodoescolar);
            echo 'Actualizado';
        case "deletebecado":

            $id = $_POST["idbecado"];
            $id_alumno = $_POST["idalumno"];

            $becados = $alumnos->delete_becados_alumnos($id);
            echo $id_alumno;

            break;
        case "analizarbeca":
            $base_url='http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI'].'?').'/';
            $id = $_POST["idalumno"];
            $cantbecas = $alumnos->get_becas_by_periodo($id);
            require_once("../views/becas_analizadas.phtml");
            break;

    }




 }


if (isset($_FILES['file']['name'])) {
    $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $tabla = basename($_FILES['file']['name'], '.'.$extension);



    if (0 < $_FILES['file']['error']) {
        echo 'Error during file upload' . $_FILES['file']['error'];

    } else {

       move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . $_FILES['file']['name']);
       //echo 'File successfully uploaded : uploads/' . $_FILES['file']['name'];

        $xlsx = new SimpleXLSX( 'uploads/'.$_FILES['file']['name']);//Instancio la clase y le paso como parametro el archivo a leer
        $fp = fopen( 'uploads/'.$tabla.'.csv', 'w');//Abrire un archivo "datos.csv", sino existe se creara

        foreach( $xlsx->rows() as $fields ) {//Itero la hoja de calculo
            fputcsv( $fp, $fields);//Doy formato CSV a una línea y le escribo los datos
        }
        fclose($fp);//Cierro el archivo "datos.csv"

        $sql = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA LIKE '$db'";
        $result = $conexion->query($sql);
        $tables = $result->fetch_all(MYSQLI_ASSOC);

        foreach($tables as $table)
        {
           $tablas[] = $table['TABLE_NAME'];
        }

        if (in_array($tabla, $tablas)) {

        //$conexion->query("TRUNCATE TABLE ".$tabla);
        //$conexion->query("ALTER TABLE ".$tabla." AUTO_INCREMENT = 0" );

            switch ($tabla)
            {
                case "alumnos":
                    try {
                        $cant = $alumnos->get_totals($tabla);
                        if ($cant==0){

                            $file = fopen('uploads/'.$tabla.'.csv', "r");
                            $i=0;
                            $status=false;
                            while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE){
                                $i++;
                                if($i != 1){

                                    $sql = "INSERT into ".$tabla." (id,matricula,nombre,carrera) values('$emapData[0]','$emapData[1]','$emapData[2]','$emapData[3]')";
                                    if ($conexion->query($sql) === TRUE) {$status = true;}
                                }
                            }
                            fclose($file);
                            //echo $status;
                        }
                        if($cant>0){
                            $file = fopen('uploads/'.$tabla.'.csv', "r");
                            $i=0;
                            $ii=0;
                            $status=false;
                            while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
                            {
                                $i++;


                                if($i != 1)
                                {
                                    $ii++;

                                    $sql = "UPDATE $tabla SET id='".$emapData[0]."',matricula = '".$emapData[1]."', nombre = '".$emapData[2]."',carrera = '".$emapData[3]."' WHERE id=".$ii;
                                    if ($conexion->query($sql) === TRUE) {

                                        $status = true;
                                    }
                                }

                            }
                            fclose($file);
                            echo $status;
                        }

                    }
                    catch (Exception $e) {
                        echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                    }
                    break;
                case "becados":
                    try {
                        $cant = $alumnos->get_totals($tabla);
                        if ($cant==0){

                            $file = fopen('uploads/'.$tabla.'.csv', "r");

                           $i=0;
                           $status=false;
                           while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE){
                               $i++;
                               if($i != 1){
                                   $sql = "INSERT into ".$tabla." (id,id_alumno,matricula,tipobeca,periodoescolar) values('$emapData[0]','$emapData[1]','$emapData[2]','$emapData[3]','$emapData[4]')";
                                   if ($conexion->query($sql) === TRUE) {$status = true;}
                               }
                           }
                           fclose($file);
                           echo $status;
                       }
                        if($cant>0){
                            $file = fopen('uploads/'.$tabla.'.csv', "r");
                           $i=0;
                            $ii=0;
                           $status=false;
                           while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
                           {
                               $i++;


                               if($i != 1)
                               {
                                   $ii++;

                                   $sql = "UPDATE $tabla SET id='".$emapData[0]."',id_alumno='".$emapData[1]."',matricula = '".$emapData[2]."', tipobeca = '".$emapData[3]."',periodoescolar = '".$emapData[4]."' WHERE id=".$ii;
                                  if ($conexion->query($sql) === TRUE) {

                                      $status = true;
                                   }
                               }

                           }
                            fclose($file);
                            echo $status;
                       }
                    }
                    catch (Exception $e) {
                        echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                    }

                    break;

            }

        }
        else{
            echo "Lo sentimos el nombre del archivo no cohencide con el nombre de alguna tabla";
        }
    }




} else {
    //echo 'Please choose a file';
}



