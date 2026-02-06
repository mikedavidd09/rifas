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
            <th>VENDEDOR</th>
            <th>JUEGO</th>
            <th>JUEGO</th>
            <th>SORTEO</th>
            <th>TOTAL</th>
        </tr>
        </thead>
    </table></p>
</div>

<script type="text/javascript" src="assets/js/cargaAjax.js"></script>

<script>

    document.addEventListener("DOMContentLoaded", function(event) {
       

    $.getScript("resources/js/jsDatatable.js", function(){

    DatatableJs('#ventas',"index.php?controller=juego&action=getVentas",0,[{

        "targets": 1,

        "render": function ( data, type, row, meta ) {

            var itemID = row[0];

            return '<a class="link" href="index.php?controller=juego&action=ver_datos&obj=' + itemID + '">' + data + '</a>';

        }

      }])

    });


 });

</script>