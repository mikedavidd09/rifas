<div class="row g-12">
    <div class="col-md-4">
       <div class="container bootstrap snippet">
  <div class="row">
    <div class="col-md-4 col-sm-4">
      <div class="circle-tile ">
        <a href="#"><div class="circle-tile-heading blue"><i class="fa fa-dollar fa-fw fa-3x"></i></div></a>
        <div class="circle-tile-content blue">
          <div class="circle-tile-description text-faded"> Facturado Semanal</div>
          <div class="circle-tile-number text-faded "><?php echo $facturado_semanal; ?></div>
          <a class="circle-tile-footer" href="#">More Info<i class="fa fa-chevron-circle-right"></i></a>
        </div>
      </div>
    </div>
  </div>
</div>
    </div>
    <div class="col-md-4">
       <div class="container bootstrap snippet">
  <div class="row">
    <div class="col-md-4 col-sm-4">
      <div class="circle-tile ">
        <a href="#"><div class="circle-tile-heading green"><i class="fa fa-dollar fa-fw fa-3x"></i></div></a>
        <div class="circle-tile-content green">
          <div class="circle-tile-description text-faded"> Pagado Semanal</div>
          <div class="circle-tile-number text-faded "><?php echo $pagado_semanal; ?></div>
          <a class="circle-tile-footer" href="#">More Info<i class="fa fa-chevron-circle-right"></i></a>
        </div>
      </div>
    </div>
  </div>
</div>
    </div>
    <div class="col-md-4">
     <div class="container bootstrap snippet">
  <div class="row">
    <div class="col-md-4 col-sm-4">
      <div class="circle-tile ">
        <a href="#"><div class="circle-tile-heading red"><i class="fa fa-users fa-fw fa-3x"></i></div></a>
        <div class="circle-tile-content red">
          <div class="circle-tile-description text-faded"> Total Usuarios</div>
          <div class="circle-tile-number text-faded "><?php echo $total_usuarios; ?></div>
          <a class="circle-tile-footer" href="#">More Info<i class="fa fa-chevron-circle-right"></i></a>
        </div>
      </div>
    </div>
  </div>
</div>
    </div>
</div>
<div class="row g-12">
    <div class="col-md-4">
        <div class="card">

            <div class="alert alert-success" role="alert">
                <h3 class="alert-heading">C$ <?php echo $facturado_dia; ?></h3>
                <h4 class="alert-heading">Facturado del Dia</h4>
            </div>
            <div class="card-body">
            
                    <?php
                    if($facturado_dia_By_juego)
                    foreach($facturado_dia_By_juego as $item){ ?>
                        <div class="alert alert-info">
                            <a href="#" class="btn btn-xs btn-primary pull-right">C$ <?php echo number_format($item->facturado); ?></a>
                            <strong>JUEGO:</strong> <?php echo $item->juego; ?>
                        </div>
                    <?php } ?>

            </div>
            <div class="card-footer">
            </div>
        </div>
    </div>  
    <div class="col-md-4">
        <div class="card">
            <div class="alert alert-success" role="alert">
                <h3 class="alert-heading"> C$ <?php echo $pagado_dia; ?></h3>
                <h4 class="alert-heading">Pagado del Dia</h4>
            </div>
            <div class="card-body">
                <?php if($pagado_dia_By_juego)
                foreach($pagado_dia_By_juego as $item){ ?>
                    <div class="alert alert-danger">
                        <a href="#" class="btn btn-xs btn-danger pull-right">C$ <?php echo number_format($item->pagado); ?></a>
                        <strong>JUEGO:</strong> <?php echo $item->juego; ?>
                    </div>
                <?php } ?>

            </div>
            <div class="card-footer">
            </div>
        </div>
    </div>  
    <div class="col-md-4">
     <div class="card">

     <?php

     if($facturado_By_Usuarios)
     foreach($facturado_By_Usuarios as $item){ ?>
        <div class="alert alert-info">
            <a href="#" class="btn btn-xs btn-warning pull-right">C$ <?php echo number_format($item->pagado); ?></a>
            <a href="#" class="btn btn-xs btn-primary pull-right">C$ <?php echo number_format($item->facturado); ?></a>
            <strong><?php echo $item->vendedor; ?></strong>
        </div>
        <?php } ?>

     </div>

</div>  

    

</div>  


<style>
.circle-tile {
    margin-bottom: 15px;
    text-align: center;
}
.circle-tile-heading {
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-radius: 100%;
    color: #FFFFFF;
    height: 80px;
    margin: 0 auto -40px;
    position: relative;
    transition: all 0.3s ease-in-out 0s;
    width: 80px;
}
.circle-tile-heading .fa {
    line-height: 80px;
}
.circle-tile-content {
    padding-top: 50px;
}
.circle-tile-number {
    font-size: 26px;
    font-weight: 700;
    line-height: 1;
    padding: 5px 0 15px;
}
.circle-tile-description {
    text-transform: uppercase;
}
.circle-tile-footer {
    background-color: rgba(0, 0, 0, 0.1);
    color: rgba(255, 255, 255, 0.5);
    display: block;
    padding: 5px;
    transition: all 0.3s ease-in-out 0s;
}
.circle-tile-footer:hover {
    background-color: rgba(0, 0, 0, 0.2);
    color: rgba(255, 255, 255, 0.5);
    text-decoration: none;
}
.circle-tile-heading.dark-blue:hover {
    background-color: #2E4154;
}
.circle-tile-heading.green:hover {
    background-color: #138F77;
}
.circle-tile-heading.orange:hover {
    background-color: #DA8C10;
}
.circle-tile-heading.blue:hover {
    background-color: #2473A6;
}
.circle-tile-heading.red:hover {
    background-color: #CF4435;
}
.circle-tile-heading.purple:hover {
    background-color: #7F3D9B;
}
.tile-img {
    text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.9);
}

.dark-blue {
    background-color: #34495E;
}
.green {
    background-color: #16A085;
}
.blue {
    background-color: #2980B9;
}
.orange {
    background-color: #F39C12;
}
.red {
    background-color: #E74C3C;
}
.purple {
    background-color: #8E44AD;
}
.dark-gray {
    background-color: #7F8C8D;
}
.gray {
    background-color: #95A5A6;
}
.light-gray {
    background-color: #BDC3C7;
}
.yellow {
    background-color: #F1C40F;
}
.text-dark-blue {
    color: #34495E;
}
.text-green {
    color: #16A085;
}
.text-blue {
    color: #2980B9;
}
.text-orange {
    color: #F39C12;
}
.text-red {
    color: #E74C3C;
}
.text-purple {
    color: #8E44AD;
}
.text-faded {
    color: rgba(255, 255, 255, 0.7);
}


</style>


