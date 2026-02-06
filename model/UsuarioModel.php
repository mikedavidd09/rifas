<?php

class UsuarioModel extends ModeloBase
{
    private $table;

    public function __construct($adapter)
    {
        $this->table = "usuarios";
        parent::__construct($this->table, $adapter);
    }

    public  function  getUsername($userName){
        $query = "SELECT *FROM `usuarios` where usuario = '$userName'";
        $userExist = $this->ejecutarSql($query);
        return is_object($userExist)? ['userExits'=>true] : ['userExits'=>false] ;
    }

    public  function  getArqueoByIdColaborador($id,$fecha){
        $query = "SELECT 1 FROM `arqueo` where SUBSTRING( fecha_time_begin,1, 10) = '$fecha' and id_colaborador=$id and estado<>'borrado'";
        $tiene_arqueo = $this->ejecutarSql($query);
        return is_object($tiene_arqueo)? true : false;
    }

    public  function  tieneSessionAbierta($id){
        $query = "SELECT *FROM `sessiones` where id_usuario= $id";
        $tiene_session = $this->ejecutarSql($query);
        return is_object($tiene_session)? ['data_session'=>$tiene_session,'tiene_session'=>true] : ['data_session'=>'','tiene_session'=>false] ;
    }

  public function getUserLogine($user, $clave)
    {
        $id = '';
        $usuario = '';
        $clave_ = '';
        $imagen = '';
        $id_colaborador = '';
        $id_sucursal='';
        $role='';
        $query = "SELECT
                    u.id_usuario,
                    u.usuario,
                    u.clave,
                    u.imagen,
                    u.id_colaborador,
                    c.id_sucursal,
                    r.nombre AS `role`
                FROM
                    colaboradores AS c
                INNER JOIN
                    usuarios AS u ON u.id_colaborador = c.id_colaborador
                INNER JOIN
                    roles AS r ON r.id_role = u.id_role
                WHERE
                    usuario =  ?";
        /*$query="SELECT s.id_sucursal, u.id_user, u.usuario, u.clave, u.imagen, u.id_colaborador, c.cargo From sucursal as s inner join colaboradores as c on s.id_sucursal=c.id_sucursal inner join usuarios as u on c.id_colaborador=u.id_colaborador where usuario = ?";*/
        $mysqli = $this->db();
        $sentencia = $mysqli->prepare($query);
        $sentencia->bind_param('s', $user);
        $sentencia->execute();
        $sentencia->bind_result($id, $usuario, $clave_, $imagen, $id_colaborador,$id_sucursal,$role);
        $usuario_array = array();
        $usuario_objet = '';
        while ($sentencia->fetch()) {
            $tmp = array();
            $tmp["id_user"] = $id;
            $tmp["usuario"] = $usuario;
            $tmp["clave"] = $clave_;
            $tmp["imagen"] = $imagen;
            $tmp["id_colaborador"] = $id_colaborador;
            $tmp["id_sucursal"] = $id_sucursal;
            $tmp["role"] = $role;
            $tmp = (object)$tmp;
            $usuario_objet = $tmp;
            array_push($usuario_array, $tmp);
        }
        $sentencia->close();
        $verify = password_verify($clave, $clave_);
        $result = count($usuario_array) > 1 ? $usuario_array : $usuario_objet;
        return $verify ? $result : [];
    }

    public function AsignarSessionStar($login, $DataSet)
    {
        $_SESSION[$login] = $DataSet;
        //print_r($_SESSION[$login]);
    }
    public function getPermisosByModulo($modulo,$id_user)
    {
        $query = "select m.permisos from permisos m inner join modules ms on ms.id_modules=m.id_modules WHERE ms.label ='$modulo' and m.id_usuarios=$id_user";
        $permisos = $this->ejecutarSqlReports($query);
		$per = isset($permisos['result']->permisos) ? $permisos['result']->permisos:"";
        $permisos = explode(',',$per);
        return $permisos;
    }
}

?>
