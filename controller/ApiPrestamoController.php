<?php

require_once 'core/Utils.php';
require_once 'core/Filtro.php';
class ApiPrestamoController extends ControladorBase
{
    public $conectar;
    public $adapter;
    protected $permisos;

    public function __construct()
    {
        parent::__construct();
        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
        header("Content-Type: application/json; charset=UTF-8");
    }

    public function getPrestamoBycode($codigo_prestamo) {
        require_once "model/PagosModel.php";
        $respuesta = new PagosModel($this->adapter);
        $respuesta = $respuesta->getDataSaldoPendienteRespApiCodigo($codigo_prestamo);
        echo json_encode(["status" => "success", "data" => $respuesta]);
    }

    public function getPlanDePagoBycode($codigo_prestamo) {
        require_once "model/PagosModel.php";
        $respuesta = new PagosModel($this->adapter);
        $respuesta = $respuesta->getDataHistorialPagosRespApiCodigo($codigo_prestamo);
        echo json_encode(["status" => "success", "data" => $respuesta]);
    }

    public function getPrestamoByCedula($cedula) {
        require_once "model/PagosModel.php";
        $respuesta = new PagosModel($this->adapter);
        $respuesta = $respuesta->getDataSaldoPendienteRespApi($cedula);
        echo json_encode(["status" => "success", "data" => $respuesta]);
    }

    public function getPlanDePagoByCedula($cedula) {
        require_once "model/PagosModel.php";
        $respuesta = new PagosModel($this->adapter);
        $respuesta = $respuesta->getDataHistorialPagosRespApiCedula($cedula);
        echo json_encode(["status" => "success", "data" => $respuesta]);
    }

    public function consultarDisponibilidadReprestamo($cedula) {
        require_once "model/PagosModel.php";
        $respuesta = new PagosModel($this->adapter);
        $respuesta = $respuesta->getDataPorcentajeDeDeudaResApi($cedula);
       // var_dump($respuesta);
        echo json_encode(["status" => "success", "data" => $respuesta]);
    }
/*
    public function createPrestamo() {
        require_once "model/Prestamo.php";
        $prestamo = new Prestamo();

        $input = json_decode(file_get_contents("php://input"), true);
        $result = $prestamo->create($input);

        echo json_encode([
            "status" => $result ? "success" : "error"
        ]);
    }
*/
}
