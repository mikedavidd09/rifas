<?php
class ModeloBase extends EntidadBase{
  private $table;
  private $fluent;

  public function __construct($table,$adapter){

    $this->table=(string) $table;
    parent::__construct($table,$adapter);

   // $this->fluent=$this->getConectar()->startFluent();
  }

  public function fluent(){
    return $this->fluent;
  }

  public function ejecutarSql($query)
  {
    $query = $this->db()->query($query);
    if ($query == true) {
      if ($query->num_rows > 1) {
        while ($row = $query->fetch_object()) {
          $resultSet[] = $row;
        }
      } elseif ($query->num_rows == 1) {
        if ($row = $query->fetch_object()) {
          $resultSet = $row;
        }
      } else {
        $resultSet = false;
      }
    } else {
      $resultSet = false;
    }
    return $resultSet;
  }

  public function ejecutarSqlRow($query)
  {
    $query = $this->db()->query($query);
    if ($query == true) {
      if ($query->num_rows > 1) {
        while ($row = $query->fetch_row()) {
          $resultSet[] = $row;
        }
      } elseif ($query->num_rows == 1) {
        if ($row = $query->fetch_row()) {
          $resultSet []= $row;
        }
      } else {
        $resultSet = false;
      }
    } else {
      $resultSet = false;
    }
    return $resultSet;
  }

  public function TotalRecord($query){
      $query = $this->db()->query($query);
      return $query->num_rows;
  }

  public function ejecutarSqlReports($query)
{
    $query = $this->db()->query($query);
    $field = $query->fetch_fields();
    if ($query == true) {
        if ($query->num_rows > 1) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
        } elseif ($query->num_rows == 1) {
            if ($row = $query->fetch_object()) {
                $resultSet = $row;
            }
        } else {
            $resultSet = false;
        }
    } else {
        $resultSet = false;
    }
    return ["result"=>$resultSet,"field"=>$field];
}
public function ejecutarSqlUpdate($query){
      $query = $this->db()->query($query);
      return $query;
  }
}
//Aqui podemos montarnos métodos para los modelos de consulta

 ?>
