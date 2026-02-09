<link href="resources/css/dataTableFiltrerByfield.css" rel="stylesheet" />
<script src="assets/js/pace.min.js"></script>

<div class="card">
    <div id="alert"></div><!-- AREA DE LOS MENSAJES DE ALERTA OBLIGATORIO-->
    <h3>Historial de Ventas</h3>
    <hr>
    <p><table id="ventas" class="table table-striped table-bordered" cellpadding="0" cellspacing="0" border="0"  width="100%">
        <thead>
        <tr>
            <th> ID </th>
            <th>CONSECUTIVO</th>
            <th>VENDEDOR</th>
            <th>CLIENTE</th>
            <th>JUEGO</th>
            <th>SORTEO</th>
            <th>FECHA</th>
            <th>HORA</th>
            <th>TOTAL</th>
        </tr>
        </thead>
    </table></p>
</div>

<script type="text/javascript" src="assets/js/cargaAjax.js"></script>

<script>

        console.log("DOM fully loaded and parsed");

    $.getScript("resources/js/jsDatatable.js", function(){

    DatatableJs('#ventas',"index.php?controller=Venta&action=getVentas",0,[{

        "targets": 1,

        "render": function ( data, type, row, meta ) {

            let itemID = row[0];

            return '<a class="link" href="index.php?controller=Venta&action=ver_venta&id=' + itemID + '">' + data + '</a>';

        }

      }])

    });


</script>