<?php
/**
 * Created by PhpStorm.
 * User: Toshiba
 * Date: 27/03/2018
 * Time: 21:43
 */

//Llamada al modelo
require_once("models/alumnos_model.php");

//Se instancia el objeto alumnos
$alumnos=new alumnos_model();

$datos=$alumnos->get_alumnos();


$base_url='http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI'].'?').'/';
//Llamada a la vista
require_once("views/alumnos_view.phtml");





//Se instancia el objeto alumnos_select
//$alumnos_select=new alumnos_model();
//$datos=$alumnos_select->get_alumnos_select();

//Llamada a la vista
//require_once("views/alumnos_view.phtml");
