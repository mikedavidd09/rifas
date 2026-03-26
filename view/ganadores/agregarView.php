<div class="card">
   <div class="card-header">
      <h3 class="card-title">Agregar Numeros Ganadores</h3>
   </div>
   <div class="card-body">
      <?php 
         $columnasPorFila = 4; // Cambia a 3, 4, 6 según necesites
         $contador = 0;
         
         foreach($juegos as $juego){ 
             $maxdigits = strlen(abs($juego->max));
             $ceros = str_repeat('0', $maxdigits);
             // Abrir nueva fila cada X columnas
             if($contador % $columnasPorFila == 0) {
                 if($contador != 0) echo '</div>'; // Cerrar fila anterior
                 echo '<div class="row mb-3">'; // Abrir nueva fila
             }
         ?>
      <div class="col-md-<?php echo 12/$columnasPorFila; ?> mb-3">
         <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
               <thead>
                  <tr class="text-center">
                     <?php $colspan = $juego->id_juego == 3 ? 4 : 3; ?>
                     <th colspan="<?php echo $colspan; ?>" class="btn-primary text-white">
                        <h6 class="mb-0"><?php echo $juego->nombre; ?></h6>
                     </th>
                  </tr>
                  <tr class="btn-primary text-white">
                     <th>SORTEO</th>
                     <?php if($juego->id_juego == 3){?>
                     <th>MES</th>
                     <th>DIA</th>
                     <?php }else{ ?>
                     <th>NUMERO</th>
                     <?php } ?>
                     <th>GUARDAR</th>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach($juego->sorteos as $sorteo){ 
                     ?>
                  <tr class="text-center">
                     <td style="width: 100px;"><?php echo $sorteo->etiqueta; ?></td>
                     <?php if($juego->id_juego != 3){?>
                     <td>
                        <input type="text"  class="form-control input-underlined" 
                           name="numero" 
                           id="numero_<?php echo $juego->id_juego; ?>_<?php echo $sorteo->id_sorteo; ?>" 
                           placeholder="<?php echo $ceros ?>"
                           maxlength="<?php echo $maxdigits ?>" 
                           required oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                           value="<?php echo $sorteo->numero == NULL ? '' : $sorteo->numero; ?>"
                           ">
                     </td>
                     <?php }else{ 
                        $meses = ['Ene','Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                        $dias = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'];
                        
                        if($sorteo->numero != NULL){
                           $fecha = explode(' ',$sorteo->numero);
                           $mes_ = $fecha[1]; 
                           $dia = $fecha[0];
                        }else{$mes_ = '0';$dia = '0';}
                        
                        ?>
                     <td style="width: 40%;">
                        <select class="form-control" name="mes" id="mes_<?php echo $juego->id_juego; ?>_<?php echo $sorteo->id_sorteo; ?>" required>
                           <option value="0">Sel</option>
                           <?php foreach($meses as $mes): ?>
                           <option value="<?php echo $mes; ?>" <?php if(isset($mes_) && $mes_ == $mes){ echo 'selected'; } ?>>
                              <?php echo $mes; ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     </td>
                     <td style="width: 15%;">
                        <input type="text"  class="form-control input-underlined" 
                           name="dia" 
                           id="dia_<?php echo $juego->id_juego; ?>_<?php echo $sorteo->id_sorteo; ?>" 
                           placeholder="<?php echo $ceros ?>"
                           maxlength="2" 
                           required oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                           value="<?php echo $sorteo->numero == NULL ? '' : $dia; ?>"
                           >
                     </td>
                     <?php } ?>
                     <td style="width: 50px;"><button type="button" class="btn btn-info" onclick="guardar(<?php echo $juego->id_juego; ?>, <?php echo $sorteo->id_sorteo; ?>)"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button></td>
                  </tr>
                  <?php } ?>
               </tbody>
            </table>
         </div>
      </div>
      <?php 
         $contador++;
         } 
         echo '</div>'; // Cerrar última fila
         ?>
   </div>
</div>
<style>
   /* Input con solo borde inferior */
   .input-underlined {
   border: none !important;
   border-bottom: 2px solid #ced4da !important;
   border-radius: 0 !important;
   box-shadow: none !important;
   padding: 8px 0 !important;
   background-color: transparent !important;
   }
   .input-underlined:focus {
   border-bottom-color: #007bff !important;
   box-shadow: none !important;
   background-color: transparent !important;
   }
   .input-underlined:hover:not(:focus) {
   border-bottom-color: #6c757d !important;
   }
</style>
<script>
   function guardar(id_juego, id_sorteo){
       let numero = '';
   
       if(id_juego == 3){
           let mes = document.getElementById("mes_"+id_juego+"_"+id_sorteo).value.trim();
           let dia = document.getElementById("dia_"+id_juego+"_"+id_sorteo).value.trim();
       
           if(!dia){
               alertify.error("Ingrese un dia por favor!");
               return;
           }
           if(mes == 0){
               alertify.error("Ingrese un mes por favor!");
               return;
           }
   
           numero = String(dia) + ' '+ String(mes);
   
           dia = parseInt(dia);
   
           if (dia < 1 || dia > 31) {
           show_Notify("danger", "Error", "Dia no puede ser menor a 1 y no puede ser mayor a 31");
           return;
           }
       }
       else 
        numero = document.getElementById("numero_"+id_juego+"_"+id_sorteo).value.trim();
   
   
       if(!numero){
           alertify.error("Ingrese un numero por favor!");
           return;
       }
       console.log(numero);
   
           $.ajax({
               url: "index.php?controller=Juegos&action=guardarNumero",
               type: "POST",
               dataType: "json",
               data: {numero: numero, id_juego: id_juego, id_sorteo: id_sorteo},
               success: function(data){
   
                   if(data.status){
                       alertify.success(data.message);
                   }
                   else{
                       alertify.error(data.message);
                   }
               }
           });
   }   
</script>