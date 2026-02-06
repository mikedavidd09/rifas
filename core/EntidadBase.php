<?php

class EntidadBase
{
    private $table;
    private $db;
    private $conectar;

    public function __construct($table, $adapter)
    {
        $this->table = (string)$table;
        /*
        require_once 'Conectar.php';
        $this->conectar = new Conectar();
        $this->db=$this->conectar->conexion();
        */
        $this->conectar = null;
        $this->db = $adapter;
    }

    public function getConectar()
    {
        return $this->conectar;
    }

    public function db()
    {
        return $this->db;
    }

    public function getAll()
    {
        $resultSet = [];
        $query = $this->db->query("SELECT *FROM $this->table");
        if ($query !== FALSE) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
        }
        return $resultSet;
    }

    public function getById($id, $id_name)
    {
        $query = $this->db->query("SELECT * FROM $this->table WHERE id_$id_name=$id");
        $resultSet = '';
        if ($row = $query->fetch_object()) {
            $resultSet = $row;
        }
        return $resultSet;
    }

    public function getBy($column, $value)
    {
        $query = $this->db->query("SELECT * FROM $this->table WHERE $column='$value'");
        $resultSet = [];
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }
        return $resultSet;
    }

    public function deleteById($id, $class)
    {
        $query = $this->db->query("DELETE FROM $this->table WHERE id_$class=$id");
        return $query;
    }

    public function updateById($id, $class, $object_instance)
    {
        $data_src = $this->prepareField($object_instance, 'update');
   
        $query = $this->db->query("UPDATE $this->table SET $data_src WHERE id_$class=$id");
        return $query;
    }
    public function updateByIdColaborador($id, $class, $object_instance)
    {
        $query = $this->db->query("UPDATE $this->table SET $object_instance WHERE id_$class=$id");
        return $query;
    }

    public function Query($sql)
    {
        $query = $this->db->query($sql);
        return $query;
    }

    public function Query_($sql)
    {
        $preparado = $this->db->query($sql);
        var_dump($preparado);
        exit;
        $query = $preparado->execute();
        return $query;
    }

    public function updateSaldoPendiente($id_prestamo,$saldopendiente){

        $query = $this->db->query("update prestamos set saldo_pendiente = '$saldopendiente' where id_prestamo = '$id_prestamo'");

        return $query;
    }

    public function updateTableById($id,$campoSet,$campo,$value){

        $query = $this->db->query("update $this->table set $campoSet = '$value' where $campo = '$id'");

        return $query;
    }

    public function deleteBy($column, $value)
    {
        $query = $this->db->query("DELETE FROM $this->table WHERE $column='$value'");
        return $query;
    }

    public function put($object_instance)
    {
        $data_src = $this->prepareField($object_instance, '');
        //print_r("INSERT INTO $this->table VALUES('0$data_src')");
        $result = $this->db->query("INSERT INTO $this->table VALUES('0$data_src')");
        $result = $this->db->error !=''?$this->db->error:$result;
        return $result;
    }

    public function prepareField($object_instance, $crud)
    {
        $query = $this->db->query("SHOW COLUMNS FROM $this->table");
        $data_src = FALSE;
        $field = [];
        if ($query !== FALSE) {
            while ($row = $query->fetch_object()) {
                $atribute = $row->Field;
                if ($object_instance->$atribute != 'none') {
                    $field[] = $crud == 'update' ? $atribute . '=\'' . $object_instance->$atribute . '\'' : $object_instance->$atribute;
                }
            }
            if (count($field) > 0) {
                $data_src = $crud == 'update' ? implode(",", $field) : implode("','", $field);
            }
            return $data_src;
        } else {
            return $data_src;
        }
    }

    public function getIdNow($id)
    {
        $query = $this->db->query("SELECT MAX($id) AS id FROM $this->table");
        $row = $query->fetch_object();
        return (string)($row->id + 1);
    }

    public function getIdEnd($id)
    {
        $query = $this->db->query("SELECT MAX($id) AS id FROM $this->table");
        $row = $query->fetch_object();
        return (string)($row->id);
    }

    public function Get_MaxCambioDolar(){
      $query = $this->db->query("SELECT MAX(id_cambiodolar) AS max FROM $this->table where (estado = 'Activo' or estado = 'Desactivo')");
      $row = $query->fetch_object();
      return (string)($row->max);
}

public function insertCeros($string){
  $result = $this->db->query($string);
  $result = $this->db->error !=''?$this->db->error:$result;
  return $result;
}

    public function registrarSession($fecha,$hora,$detalles,$id_usuaio){
        $result = $this->db->query("INSERT INTO `sessiones` (`id_session`, `fecha`, `hora`, `detalles`, `id_usuario`) VALUES (null, '$fecha', '$hora','$detalles', $id_usuaio)");
        $result = $this->db->error !=''?$this->db->error:$result;
        return $result;
    }
    public function deletePagosNewByfecha_pago($fecha_pago){//modificado

        $query = $this->db->query("delete from pagos_new where  fecha_pago='$fecha_pago'");
        return $query;
    }
    public function borrarRegistroSession($id_usuaio){
        $result = $this->db->query("delete from sessiones where id_usuario = $id_usuaio");
        $result = $this->db->error !=''?$this->db->error:$result;
        setcookie(
            'session',
            '',
            time() - 3600,
            '/',
            $_SERVER['HTTP_HOST']
        );
        return $result;
    }



	public function SetLogFile($controlador,$accion,$dato){

			$login=$_SESSION["Login_View"];

			date_default_timezone_set("america/managua");

			$user =$login->usuario;
			$hora= date ("h:i:s");
			$fecha= date ("d/m/Y");

			switch($controlador){

			case "Cliente":
				$string ="Accion: ".$accion." | Usuario: ".$user." | Fecha: ".$fecha." | Hora: ".$hora." | Nombre Cliente: ".$dato. " |\n";
			break;

			case "Colaborador":
				$string ="Accion: ".$accion." | Usuario: ".$user." | Fecha: ".$fecha." | Hora: ".$hora." | Nombre Colaborador: ".$dato. " |\n";
			break;

			case "Prestamo":
				$string ="Accion: ".$accion." | Usuario: ".$user." | Fecha: ".$fecha." | Hora: ".$hora." | Codigo Prestamo: ".$dato. " |\n";
			break;

			case "Diaferiado":
				$string ="Accion: ".$accion." | Usuario: ".$user." | Fecha: ".$fecha." | Hora: ".$hora." | Dia feriado: ".$dato. " |\n";
			break;

			case "Sucursal":
				$string ="Accion: ".$accion." | Usuario: ".$user." | Fecha: ".$fecha." | Hora: ".$hora." | Nombre Sucursal: ".$dato. " |\n";
			break;

			case "Pagos":
				$string ="Accion: ".$accion." | Usuario: ".$user." | Fecha: ".$fecha." | Hora: ".$hora." | ID Prestamo : ".$dato[0]->id_prestamo." | ID Pago : ".$dato[0]->id_pago." | Monto Cuota : ".$dato[0]->monto_cuota." | Saldo Excedente : ".$dato[0]->saldo_excedente." |\n";
			break;
			case "Prendas":
				$string ="Accion: ".$accion." | Usuario: ".$user." | Fecha: ".$fecha." | Hora: ".$hora." | ID Prestamo :  | ID Pago :  | Monto Cuota :  | Saldo Excedente  |\n";
			break;
			case "Error":

				$string ="Accion: ".$accion." | Usuario: ".$user." | Fecha: ".$fecha." | Hora: ".$hora." | Error: ".$dato. " |\n";

			break;

				case "Session":

				$string ="Accion: ".$accion." | Usuario: ".$user." | Fecha: ".$fecha." | Hora: ".$hora." | Estado:".$dato. " |\n";

			break;

			}

			$fichero = fopen('logfile.txt', 'a+');
			fwrite($fichero, $string);
			fclose($fichero);


	}

	public function updateSetEstadoPrestamo($id_prestamo,$estado){
        //$query="UPDATE $this->table set estado='$estado' where id_prestamo=$id_prestamo";
        $query = $this->db->query("UPDATE $this->table set estado='$estado' where id_prestamo=$id_prestamo");
        return $query;
    }
    public function updateSetEstadoPagos($id_pago,$estado,$fecha_borrado){//modificado

        $query = $this->db->query("UPDATE $this->table set estado='$estado', fecha_borrado_cuota='$fecha_borrado' where id_pago=$id_pago");
        return $query;
    }

    public function ActualizarDatosPrestamos($mora,$saldo_pendiente,$saldo_favor,$total_abonado,$id_prestamo){//modificado
        $estado =  $saldo_pendiente < 1 ? ",estado='Cancelado'":",estado='Aceptado'";
        $query = $this->db->query("UPDATE prestamos set mora='$mora',monto_favor='$saldo_favor' , total_abonado='$total_abonado' , saldo_pendiente='$saldo_pendiente' $estado where id_prestamo=$id_prestamo");
        return $query;
    }

    public function ActualizarBarrioCliente($id, $barrio){//modificado
        $query = $this->db->query("UPDATE clientes set localidad='$barrio' where id_cliente=$id");
        return $query;
    }

    public function updateSetEstadoPagosNew($id_pago,$estado,$fecha_borrado){//modificado

        $query = $this->db->query("UPDATE pagos_new set estado='$estado', fecha_borrado_cuota='$fecha_borrado' where id_pago=$id_pago");
        return $query;
    }
    public function deletePagosNewByIdPrestamo($id_prestamp){//modificado

        $query = $this->db->query("delete from pagos_new where id_prestamo = $id_prestamp");
        return $query;
    }
    /*
     * Aquí podemos montarnos un montón de métodos que nos ayuden
     * a hacer operaciones con la base de datos de la entidad
     */
}

?>
