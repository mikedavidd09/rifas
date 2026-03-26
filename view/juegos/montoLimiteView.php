<div class="row g-4">
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title"><h3 class="card-title alert alert-success" role="alert">Monto Límite</h3></h5>
        </div>
        <div class="card-body">
         
            <form id="formMontoLimite" class="form-group">
              <?php 

              foreach($juegos as $juego){
                echo '<div class="row mb-3">';
                echo '<div class="col-md-4 col-xs-4 col-sm-4">';
                echo '<label for="nombreJuego">'.$juego->nombre.'</label>';
                echo '</div>';
                echo '<div class="col-md-4 col-xs-4 col-sm-4">';
                echo '<input type="number" class="form-control input-underlined" id="monto_limite_'.$juego->id_juego.'" aria-describedby="monto_limite" placeholder="Ingrese el monto límite" value="'.$juego->monto_limite.'">';
                echo '</div>';
                echo '<div class="col-md-4 col-xs-4 col-sm-4">';
                echo '<button type="button" class="btn btn-primary" onclick="guardarMontoLimite('.$juego->id_juego.')"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>';
                echo '</div>';
                echo '</div>';
                }

              ?>
            </form>
     
        </div>
      </div>
    </div>
</div>

<script>

  function guardarMontoLimite(id_juego){
      let montoLimite = document.getElementById("monto_limite_"+id_juego).value.trim();
  
      if(!montoLimite){
          alertify.error("Ingrese un monto límite por favor!");
          return;
      }
        $.ajax({
                url: "index.php?controller=Juegos&action=guardarMontoLimite",
                type: "POST",
                dataType: "json",
                data: {id_juego: id_juego, monto_limite: montoLimite},
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