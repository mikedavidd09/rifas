<?php
class TipoCambioMonedaModel extends ModeloBase{
    private $table;
    public function __construct($adapter){
        $this->table="tipo_cambio";
        parent::__construct($this->table,$adapter);
    }

    //Metodos de consulta
    public function setDesactivarCambioMoneda($id_tipo_cambio){
        $query="update $this->table set estado =0 where id_tipo_cambio = '$id_tipo_cambio'";
        $estado=$this->ejecutarSql($query);
        return $estado;
    }

    public function getTipoCambioMonedaActivo($id_tipo_moneda){

        $query = "SELECT tm.id_tipo_cambio,tm.monto,tm.estado FROM tipo_cambio tm where tm.id_tipo_moneda='$id_tipo_moneda' and tm.estado=1";

        $cambio = $this->ejecutarSql($query);
        return is_object($cambio)?[$cambio]:$cambio;
    }
    public function Get_CambioDolarActivo(){

        $query = "SELECT * FROM  cambiodolar  where estado = 'Activo'";
        $cambiodolar = $this->ejecutarSql($query);
        return $cambiodolar;
    }

}
?>
