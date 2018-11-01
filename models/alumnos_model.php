<?php

/**
 * Created by PhpStorm.
 * User: Toshiba
 * Date: 27/03/2018
 * Time: 21:29
 */
class alumnos_model
{
    private $db;
    private $alumnos;
    private $alumnoss;
    private $becas;
    private $becass;
    private $tipobeca;
    private $cantbeca;
    private $cantbecas;

    public function __construct()
    {
        $this->db = Conectar::conexion();
        $this->alumnos = array();
        $this->becas = array();
        $this->becass = array();
        $this->tipobeca = array();
    }

    public function get_alumnos()
    {
        $consulta = $this->db->query("select * from alumnos;");
        while ($filas = $consulta->fetch_assoc()) {
            $this->alumnos[] = $filas;
        }
        return $this->alumnos;
        $this->db = Conectar::close_conexion($this->db = Conectar::conexion());
    }

    public function get_totals($tabla)
    {
        $consulta = $this->db->query("select * from " . $tabla);

        if ($consulta) {

            /* determinar el número de filas del resultado */
            $total = $consulta->num_rows;

            return $total;

            /* cerrar el resultset */
            $consulta->close();
        }

        $this->db = Conectar::close_conexion($this->db = Conectar::conexion());
    }

    public function clean_post($val)
    {
        //Verificar que el valor no sea nullo
        if (isset($_POST[$val])) {
            $value = trim($_POST[$val]);
            // Si las comillas mágicas no se activan, agregue barras inclinadas.
            if (!get_magic_quotes_gpc()) // Adds the slashes.
            {
                $value = addslashes($value);
            }

            // Tira todas como etiquetas hacen valor.
            $value = strip_tags($value);
        }
        // Return the value out of the function.
        return $value;

    }

    public function get_alumnos_select($id)
    {
        try {
            $consulta = $this->db->query("select * from alumnos where id ='$id';");
            while ($filas = $consulta->fetch_assoc()) {
                $this->alumnos[] = $filas;
            }
            return $this->alumnos;
            $this->db = Conectar::close_conexion($this->db = Conectar::conexion());
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        /*$consulta=$this->db->query("select * from alumnos where id=$id;");
        while($filas=$consulta->fetch_assoc()){
            $this->alumnos[]=$filas;
        }
        return $this->alumnos;*/
    }

    public function get_becados_alumnos($id)
    {
        try {
            $consulta = $this->db->query("select * from becados where id_alumno ='$id';");
            while ($filas = $consulta->fetch_assoc()) {
                $this->becas[] = $filas;
            }
            return $this->becas;
            $this->db = Conectar::close_conexion($this->db = Conectar::conexion());
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        /*$consulta=$this->db->query("select * from alumnos where id=$id;");
        while($filas=$consulta->fetch_assoc()){
            $this->alumnos[]=$filas;
        }
        return $this->alumnos;*/
    }

    public function add_becados_alumnos($id)
    {
        try {

            $consulta = $this->db->query("select matricula from alumnos where id ='$id';");
            while ($filas = $consulta->fetch_assoc()) {
                $this->alumnos[] = $filas;
            }

            foreach ($this->alumnos as $dato) {
                $matricula = $dato["matricula"];

                $sql = "INSERT INTO becados (id,id_alumno,matricula,tipobeca,periodoescolar)VALUES ('', '$id', '$matricula','','')";
                $add = $this->db->query($sql);

                if ($add === TRUE) {
                    return $matricula;
                } else {
                    return 0;
                }
            }

            $this->db = Conectar::close_conexion($this->db = Conectar::conexion());
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        /*$consulta=$this->db->query("select * from alumnos where id=$id;");
        while($filas=$consulta->fetch_assoc()){
            $this->alumnos[]=$filas;
        }
        return $this->alumnos;
        $sql = "UPDATE becados SET tipobeca = '".$tipobeca."',periodoescolar = '".$periodoescolar."' WHERE id=".$id;
            $update=$this->db->query($sql);

        */
    }

    public function delete_becados_alumnos($id)
    {
        try {

            $sql = "DELETE FROM becados WHERE id=$id";
            $delete = $this->db->query($sql);
            $this->db->query("ALTER TABLE becados AUTO_INCREMENT = 0" );



            $this->db = Conectar::close_conexion($this->db = Conectar::conexion());
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        /*$consulta=$this->db->query("select * from alumnos where id=$id;");
        while($filas=$consulta->fetch_assoc()){
            $this->alumnos[]=$filas;
        }
        return $this->alumnos;
        $sql = "UPDATE becados SET tipobeca = '".$tipobeca."',periodoescolar = '".$periodoescolar."' WHERE id=".$id;
            $update=$this->db->query($sql);

        */
    }

    public function get_becas_by_periodo($id){


        try {
            $sql="SELECT COUNT(id_alumno) AS cantidad, periodoescolar FROM becados WHERE id_alumno=".$id." GROUP BY periodoescolar";
            $consulta = $this->db->query($sql);
            while ($filas = $consulta->fetch_assoc()) {
                $this->cantbeca[] = $filas;
            }
            return $this->cantbeca;
            $this->db = Conectar::close_conexion($this->db = Conectar::conexion());
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function update_becados_alumnos($id,$tipobeca,$periodoescolar)
    {
        try {

            $sql = "UPDATE becados SET tipobeca = '".$tipobeca."',periodoescolar = '".$periodoescolar."' WHERE id=".$id;
            $update=$this->db->query($sql);

            $this->db = Conectar::close_conexion($this->db = Conectar::conexion());
        } catch (Exception $e) {
            echo $e->getMessage();
        }


    }

    public function get_becas_by_alumnos()
    {
        $consulta = $this->db->query("select * from alumnos;");
        while ($filas = $consulta->fetch_assoc()) {
            $this->alumnoss[] = $filas;
        }
        return $this->alumnoss;



        $this->db = Conectar::close_conexion($this->db = Conectar::conexion());
    }

}