<link href="resources/css/dataTableFiltrerByfield.css" rel="stylesheet" />
<script src="assets/js/pace.min.js"></script>

<div class="card">
    <div id="alert"></div><!-- AREA DE LOS MENSAJES DE ALERTA OBLIGATORIO-->
    <h3>Ganadores</h3>
    <hr>
    <p><table id="ganadores" class="table table-striped table-bordered" cellpadding="0" cellspacing="0" border="0"  width="100%">
        <thead>
        <tr>
            <th> ID </th>
            <th>VENDEDOR</th>
            <th>CLIENTE</th>
            <th>CONSECUTIVO</th>
            <th>JUEGO</th>
            <th>SORTEO</th>
            <th>FECHA</th>
            <th>HORA</th>
            <th>VENTA</th>
            <th>NUMERO</th>
            <th>PREMIO</th>
            <th>ESTADO</th>
            <th>PAGAR</th>
        </tr>
        </thead>
    </table></p>
</div>

<script type="text/javascript" src="assets/js/cargaAjax.js"></script>
<script type="text/javascript" src="assets/js/loto/alert.js"></script>

<script>

    console.log("DOM fully loaded and parsed");

    $.getScript("resources/js/jsDatatable.js", function(){

    DatatableJs('#ganadores',"index.php?controller=Juegos&action=getGanadores",0,[{

        "targets": 1,

        "render": function ( data, type, row, meta ) {

            let itemID = row[0];

            return '<a class="link" href="index.php?controller=Venta&action=ver_venta&id=' + itemID + '">' + data + '</a>';
        }
    },
    {
        "targets": 11,

        "render": function ( data, type, row, meta ) {

            if(data == 1)
                return '<span class="badge bg-info rounded-pill">PAGADO</span>';
            else
                return '<span class="badge bg-danger rounded-pill">PENDIENTE</span>';
        }

    },
    {
        "targets": 12,

        "render": function ( data, type, row, meta ) {

        let id_venta = row[0];
        url = "index.php?controller=Venta&action=pagarPremio&id_venta=" + id_venta;
        $message = '¿Está Seguro de realizar esta accion ?';
        $table = "ganadores";
        return '<button type="button" class="btn btn-info btn-alert" onclick = "alertConfirm(\'' + url + '\',\'' + $message + '\' ,\'' + $table + '\')"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>';
        }
    },
    
    ])

    });

</script>

