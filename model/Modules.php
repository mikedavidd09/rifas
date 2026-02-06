<?php
class Modules extends EntidadBase{
    public $id_modules;
    public $label;
    public $nombre;
    public $modules_url;
    public $type;
    public $font_icon;
    public $id_parent;
    public $sort;

    public function __construct($adapter){
        $table="modules";
        parent::__construct($table,$adapter);
    }

    
}
?>
