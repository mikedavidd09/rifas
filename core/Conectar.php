<?php
class Conectar{
    private $driver;
    private $host, $user, $pass, $database, $charset;
    
    public function __construct(){
      $db_cfg = require 'config/database.php';
      $this->driver=$db_cfg["driver"];
      $this->host=$db_cfg["host"];
      $this->user=$db_cfg["user"];
      $this->pass=$db_cfg["pass"];
      $this->database=$db_cfg["database"];
      $this->charset=$db_cfg["charset"];
    }

  public function conexion(){
    $con="";
  if($this->driver=="mysql" || $this->driver=="null"){
    $con = new mysqli($this->host,$this->user,$this->pass,$this->database);
    $con->query("SET NAMES '".$this->charset."'");
  }
  return $con;
  }
  public function startFluent(){
    //  requiere_once "FluentPDO/FluentPDO.php";
  if($this->driver=="mysql" || $this->driver==null){
    $pdo = new PDO($this->driver.":dbname=".$this->database, $this->user, $this->pass);
    $fpdo = newFluentPDO($pdo);
    }
    return $fpdo;
  }

  public function conexionPDO()
{
    $dsn = "{$this->driver}:host={$this->host};dbname={$this->database};charset={$this->charset}";
    try {
        $pdo = new PDO($dsn, $this->user, $this->pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        die("Error de conexión a la base de datos: " . $e->getMessage());
    }
}




}
?>
