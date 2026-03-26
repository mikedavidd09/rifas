<link href="resources/css/dataTableFiltrerByfield.css" rel="stylesheet" />
<script src="assets/js/pace.min.js"></script>

<div class="card">
    <div id="alert"></div><!-- AREA DE LOS MENSAJES DE ALERTA OBLIGATORIO-->
    <h3>Historial de Ventas</h3>
    <hr>
<div class="row">
    <div class="col-md-2 col-sm-2">
        <div class="form-group">
            <label for="fecha">Fecha</label>
            <input type="date" class="form-control" id="fecha" name="fecha" placeholder="Fecha" required
                   style="height: calc(1.5em + 1.5rem + 2px); padding: 0.25rem 0.5rem; font-size: 0.875rem;">
        </div>
    </div>
</div>

    <p><table id="ventas" class="table table-striped table-bordered" cellpadding="0" cellspacing="0" border="0"  width="100%">
        <thead>
        <tr>
            <th> ID </th>
            <th>CLIENTE</th>
            <th>JUEGO</th>
            <th>SORTEO</th>
            <th>CONSECUTIVO</th>
            <th>VENDEDOR</th>
            <th>FECHA</th>
            <th>HORA</th>
            <th>TOTAL</th>
            <th> ESTADO</th>
        </tr>
        </thead>
    </table></p>
</div>

<script type="text/javascript" src="assets/js/cargaAjax.js"></script>

<script>

    console.log("DOM fully loaded and parsed");

    $.getScript("resources/js/jsDatatable.js", function(){

    DatatableJs('#ventas',"index.php?controller=Venta&action=getVentas",0,[
        {
            
        "targets": 1,

        "render": function ( data, type, row, meta ) {

            let itemID = row[0];

            return '<a class="link" href="index.php?controller=Venta&action=ver_venta&id=' + itemID + '">' + data + '</a>';

        }
    },
        {
        "targets": 9,

        "render": function ( data, type, row, meta ) {

        let estado = row[9];

            if(estado == 1){
                return '<span class="badge bg-danger rounded-pill">BORRADO </span>';
            }else{
                return '<span class="badge bg-info rounded-pill">ACTIVO</span>';
            }

        }
        
      }])

    });


</script>