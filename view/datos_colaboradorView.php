<?php
   //print_r($obj);
      $imagen = $colaborador->imagen;
      $user = $colaborador->usuario;
      $pass = $colaborador->clave;
      $edad =(int)date('Y')-((int)('19'.substr($colaborador->cedula,8,2)));
      echo "<script type='text/javascript'>
                  ImagenPreview('imagen','img_cliente');
            </script>";
      ?>
<div>
   <ol class="breadcrumb text-right">
      <li><a href="index.php?controller=colaborador&action=index" class="link" >Historial de Colaboradores</a></li>
      <li class="active">Colaborador</li>
   </ol>
</div>
<div id="card" class='card'>
   <form class="form-horizontal" id='colaborador_form' method='post'>
      <ul class="nav nav-tabs">
         <li class="active"><a data-toggle="tab" href="#datosPers">Datos Personales</a></li>
         <li><a data-toggle="tab" href="#prestamos">Usuario</a></li>
      </ul>
      <div class="tab-content">
         <div id="datosPers" class="tab-pane fade in active">
            <br />
            <div class='row'>
               <div class='col-lg-6'>
                  <div class="form-group">
                     <label class="control-label col-sm-2" for="codigo">Codigo:</label>
                     <div class="col-xs-10">
                        <input class='form-control' name='codigo' value='<?php echo $colaborador->codigo; ?>' id='codigo' readOnly>
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="control-label col-sm-2" for="nombre">Nombre:</label>
                     <div class="col-xs-10">
                        <div class="row">
                           <div class="col-lg-6">
                              <input type='text' value='<?php echo $colaborador->nombre; ?>' name='nombre' class='letras form-control' />
                           </div>
                           <div class="col-lg-6">
                              <input type='text' value='<?php echo $colaborador->apellido; ?>' name='apellido' class='letras form-control' />
                           </div>
                        </div>
                     </div>
                  </div>

                                    <div class="form-group">
                     <label class="control-label col-sm-2" for="cedula">Cedula:</label>
                     <div class="col-xs-10">
                        <input type='text' value='<?php echo $colaborador->cedula; ?>' name='cedula' class='letras form-control' />
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="control-label col-sm-2" for="cedula">Edad:</label>
                     <div class="col-xs-10">
                        <input type='text' value='<?php echo $edad; ?>' name='edad' class='letras form-control' readOnly />
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="control-label col-sm-2" for="cedula">Sexo:</label>
                     <div class="col-xs-10">
                        <div id="div_btn_radio" class="div_btn_button">
                           <label class="radio-inline"><input id='sexo' type="radio" name="sexo" value="m" <?php if($colaborador->sexo == 'm'){echo "checked";}?>>Masculino</label>
                           <label class="radio-inline"><input id='sexo' type="radio" name="sexo" value="f" <?php if($colaborador->sexo == 'f'){echo "checked";}?>>Femenino</label>
                        </div>
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="control-label col-sm-2" for="cedula">Telefono:</label>
                     <div class="col-xs-10">
                        <input type='text' value='<?php echo $colaborador->telefono; ?>' name='telefono' class='letras form-control' />
                     </div>
                  </div>

                      <div class="form-group">
                     <label class="control-label col-sm-2" for="cedula">Direccion:</label>
                     <div class="col-xs-10">
                        <textarea class='form-control' name='direccion' rows='4' id='direccion' ><?php echo $colaborador->direccion; ?></textarea>
                     </div>
                  </div>

               </div>
               <div class='col-lg-6'>
                    <div class="form-group">
                     <label>Foto Colaborador: </label>
                     <div class="col-xs-10">
                        <input class="file-input" name="imagen" id="imagen" accept="image/*"  type="file" />
                        <input type='hidden' id='img_old' name='img_old' value='<?php echo $imagen; ?>'>
                     </div>
                  </div>
                  <div class="form-group">
                     <div class="col-xs-10">
                        <img id='img_cliente' src='assets/img/fotos_colaborador/<?php echo $imagen; ?>'  width='65%' height='65%' class='img-thumbnail' /><br>
                     </div>
                  </div>
               </div>
            </div>
    
         </div>
         <div id="prestamos" class="tab-pane fade">
            <br />
            <div class='row'>
               <div class='col-lg-5'>
                  <br />
                  <div class="form-group">
                     <label class="control-label col-sm-2" for="cedula">Fecha Ingreso:</label>
                     <div class="col-xs-10">
                        <div class='input-group date'>
                           <input id="fecha_ingreso" name="fecha_ingreso" value='<?php echo $colaborador->fecha_ingreso; ?>' class="datepicker form-control"   type="text" autofocus  >
                           <span class="input-group-addon">
                           <span class="glyphicon glyphicon-calendar"></span>
                        </div>
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="control-label col-sm-2" for="cargo">Cargo:</label>
                     <div class="col-xs-10">
                        <SELECT name="id_role" id="id_role" class=" form-control">
                           <option value="0">-Seleccione-</option>
                           <?php
                              foreach ($roles as $role) {
                               if($role->id_role == $colaborador->id_role){
                                   echo "<OPTION VALUE=\"".$role->id_role."\" SELECTED>".$role->nombre."</OPTION>";
                               }else{
                                   echo "<OPTION VALUE=\"".$role->id_role."\">".$role->nombre."</OPTION>";
                               }
                              }
                              ?>
                        </SELECT>
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="control-label col-sm-2" for="cedula">Usuario:</label>
                     <div class="col-xs-10">
                        <input type="text" name="usuario"  value='<?php echo $user; ?>' class="form-control">
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="control-label col-sm-2" for="cedula">Contraseña:</label>
                     <div class="col-xs-10">
                        <div id='div_pws'>
                           <input id='pws' type="password" name="password" value='' placeholder='******' class="noValidated form-control">
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
</div>
</form>
</div>
<div id="contenedor"></div>
<?php
   echo $button;
   ?>
<script src="assets/js/bootstrap-notify.js"></script>
<script src="assets/js/pace.min.js"></script>
<script type="text/javascript">
   var $input = $( '.datepicker' ).pickadate({
       format: 'dd-mmmm-yyyy',
       formatSubmit: 'yyyy/mm/dd',
       // min: [2015, 7, 14],
       container: '#contenedor',
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