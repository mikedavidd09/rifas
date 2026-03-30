<link href="resources/css/dataTableFiltrerByfield.css" rel="stylesheet" />
<script src="assets/js/pace.min.js"></script>
<div class="row g-6">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title alert alert-success" role="alert">Borrar Números Ganadores</h3>
            </div>
            <div class="card-body">
                <form id="formBorrar" class="form-group">
                    <div class="table-responsive">
                        <table  id="numerosGanadores" class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr class="btn-primary text-white">
                                    <th>ID</th>
                                    <th>JUEGO</th>
                                    <th>SORTEO</th>
                                    <th>NÚMERO</th>
                                    <th>BORRAR</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="assets/js/cargaAjax.js"></script>
<script src="assets/js/loto/alert.js"></script>


<script>

    $.getScript("resources/js/jsDatatable.js", function(){

        DatatableJs('#numerosGanadores',"index.php?controller=NumeroGanador&action=listarBorrado",0,[
        {
            "targets": 4,
            "render": function ( data, type, row, meta ) {
                let id_numero_ganador = row[4];
                let url = 'index.php?controller=NumeroGanador&action=delete&id_numero_ganador=' + id_numero_ganador;
                let message = '¿Estás seguro que quieres borrar este número ganador?';
                let table = 'numerosGanadores';
                return '<button type="button" class="btn btn-info btn-alert" onclick = "alertConfirm(\'' + url + '\',\'' + message + '\' ,\'' + table + '\')"><i class="fa fa-trash" aria-hidden="true"></i></button>';

            }
        },
    ])
    });
</script>