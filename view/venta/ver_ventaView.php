<div class="card">
    <div id="alert"> venta</div><!-- AREA DE LOS MENSAJES DE ALERTA OBLIGATORIO-->

   <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="nombre">JUEGO</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo $venta->JUEGO; ?>" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="numero">Cliente</label>
                            <input type="text" id="numero" name="numero" class="form-control" value="<?php echo $venta->cliente; ?>" />
                        </div>
                    </div>
                 

              
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <table class="table table-striped table-bordered" cellpadding="0" cellspacing="0" border="0"  width="100%">
            <thead>
              <tr>
                <th>Nº</th>
                <th>Numero</th>
                <th>Monto C$</th>
                <th>Borrar</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              <?php foreach ($numeros as $numero) { ?>
                <tr>
                  <td><?php echo $numero->numero; ?></td>
                  <td><?php echo $numero->monto; ?></td>
                  <td><?php echo $numero->monto * 80; ?></td>
                  <td><button class="btn btn-danger" onclick="deleteNumero(<?php echo $numero['numero']; ?>)">Borrar</button></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>  
    </div>      

</div>