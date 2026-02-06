<!--CSS DATA TABLE CDN-->
<link href="resources/css/dataTableFiltrerByfield.css" rel="stylesheet" />
<link href="assets/css/buttons.dataTables.min.css" rel="stylesheet" />
<link href="resources/css/reports.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/fixedcolumns/3.2.2/css/fixedColumns.dataTables.min.css" rel="stylesheet"/>
<script type="text/javascript">
      var $input = $( '.datepicker' ).pickadate({
          format: 'dd-mmmm-yyyy',
          formatSubmit: 'yyyy/mm/dd',
          // min: [2015, 7, 14],
          container: '#container-picker',
          //editable: true,
          closeOnSelect: false,
          closeOnClear: false,
      })
      var picker = $input.pickadate('picker')
      // picker.set('select', '14 October, 2014')
      // picker.open()

      // $('button').on('click', function() {
      //     picker.set('disable', true);
      // });
  </script>
<div class="card">
    <h3>Generador Reportes</h3>

    <div class="row">
        <hr/>
         <form enctype="multipart/form-data" id="report_form" method="POST">
               <div class="col-lg-2">
                <label> <span class="txRed">*</span>Fecha Inicio: </label>
                <div class='input-group date'>
                    <input id="fecha_bengin" name="fecha_bengin" class="datepicker form-control"   type="text" autofocuss  >
                    <span class="input-group-addon">
                        <span class="fa fa-calendar"></span>
                </div>
                <div id="container-picker"></div>
            </div>
            <div class="col-lg-2">
                <label> <span class="txRed">*</span>Fecha final: </label>
                <div class='input-group date'>
                    <input id="fecha_end" name="fecha_end" class="datepicker form-control"   type="text" autofocuss  >
                    <span class="input-group-addon">
                        <span class="fa fa-calendar"></span>
                </div>
                <div id="container-picker"></div>
            </div>
            <div class="col-lg-2">
                <label>Agrupado Por: </label>
                <SELECT name="groud_by"  id="groud_by" class=" form-control">
                  <option value="0">-Seleccione-</option>
                  <option value="Prestamos">Prestamos</option>
                  <option value="Clientes">Clientes</option>
                  <option value="Gestores">Gestores</option>
                </SELECT>
            </div>
            <div class="col-lg-2">
                <label>Filtro: </label>
                <SELECT name="filter_by"  id="filter_by" class=" form-control">
                    <option value="0">-Seleccione-</option>
                </SELECT>
            </div>
            <div style="display:none;" id="colaboradores_div" class="noValidated">
            <div style="display:none;" id="colaborador" class="col-lg-2">
              <label><span class="txRed">*</span>Gestor de Cobro:</label>
              <select id="id_colaborador" name="id_colaborador" class="form-control noValidated">
                  <option value="0">-Seleccione-</option>
              </select>
            </div>
            <div  class="col-lg-2">
              <div class='toggle'><label><input id="all_colaborador" type="checkbox" checked ><span class='button-indecator'>Todos</span></label></div>
            </div>
          </div>
          <div style="display:none;" id="codigo_cliente_div" class="noValidated col-lg-2">
            <label><span class="txRed">*</span> Cliente: </label>
                      <input type="search" list="resultado" name="codigo_cliente" id="busqueda" class=" form-control" />
                      <datalist id="resultado"></datalist>
                      <div id="msj_error"> </div>
          </div>
          <div class="col-lg-2">
              <label> <span class="txRed">*</span>Sucursal: </label>
              <select name = "id_sucursal" id="id_sucursal" class=" form-control">
                 <option value=0>---Seleccione---</option>
              </select>
          </div>
        </form>
    </div><br>
        <div id="conteiner_reportes"></div>
    </div>

    <div id="contenedor"></div>
    <?php
    $url = "controller=Reports&action=generarReports";
    $url2 = "controller=Colaborador&action=dataByComboAnalista";

    echo "<script>
            getOptionCombo('Colaborador','dataByComboAnalista','id_colaborador','id_colaborador','nombre','apellido','');
        </script>";
    ?>
    <div id="inferior">
        <input href="#" type="submit" value="Generar" class="btn btn-success" onclick="enviarReports('<?php echo $url?>');return false;">
        <button  class="botonExcel btn btn-warning" title="Con Sumaoria de Totales al final" ><span><i class="fa fa-file-excel-o"></i></span>Expor Exel</button>
        <form action="ficheroExcel.php" method="post" target="_blank" id="FormularioExportacion">
            <input type="hidden" class="botonExcel" id="datos_a_enviar" name="datos_a_enviar" />
        </form>    </div>
    <script src="assets/js/bootstrap-notify.js"></script>
    <script src="assets/js/pace.min.js"></script>
    <script src="resources/js/reportes.js"></script>
    
    
