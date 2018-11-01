<?php

/**
 * Created by PhpStorm.
 * User: Toshiba
 * Date: 27/03/2018
 * Time: 21:21
 */
class conectar{


    public static function getdb(){
        return 'avances';
    }

    public static function conexion(){
        $conexion=new mysqli("localhost", "root", "", "avances");
        $conexion->query("SET NAMES 'utf8'");
        return $conexion;
    }



    public static function close_conexion($x){

            mysqli_close($x);
            }



}