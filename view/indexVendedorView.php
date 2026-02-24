<html>
   <head>
      <link rel="shortcut icon" href="1c050bc132.ico">
      <meta charset="utf-8">

      <!--Chrome,Firefox OS,Opera and Viladi-->
      <meta name="theme-color" content="#00635a">
      <!--Windows Phone-->
      <meta name="msapplication-navbutton-color" content="#57383d">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- CSS-->
      <link id="theme-style" rel="stylesheet" type="text/css" href="assets/css/main.css">
      <link rel="stylesheet" type="text/css" href="assets/css/form-css.css">
      <script defer src="resources/js/changeTheme.js"></script>
      <!--CSS DATA TABLE CDN-->
      <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet"/>
      <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet"/>
      <link href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css" rel="stylesheet"/>
      <link rel="stylesheet" href="assets/css/picker/default.css">
      <link rel="stylesheet" href="assets/css/picker/default.date.css">
      <!-- Font-icon css-->
      <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
      <!--Alerta CSS-->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.11.1/css/alertify.min.css"/>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.11.1/css/themes/default.min.css"/>
      <link rel="stylesheet" href="assets/js/morris/morris.css">
      <style>
         .badge {
         background-color: #fa3e3e !important;
         margin-right: 20px!important;
         }
         .mb-custom {
         margin-bottom: 1.5rem; /* o el valor que desees */
         }
      </style>
      <title>RIFAS AEL REGALON</title>
      <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries-->
      <!--if lt IE 9
         script(src='https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js')
         script(src='https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js')
         -->
   </head>
   <body class="sidebar-mini fixed">
      <?php 
         $login=$_SESSION["Login_View"];
         ?>
      <div class="wrapper">
      <div id="cover-spin" style="display: none;"></div>
      <!-- Navbar-->
      <header class="main-header hidden-print">
         <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button--><a class="sidebar-toggle" href="#" data-toggle="offcanvas"></a> 
            <!-- Navbar Right Menu-->
            <div class="navbar-custom-menu">
               <ul class="top-nav">

                    
                  <li class="dropdown notification-menu">
                     <a class="dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-moon-o" aria-hidden="true"></i></a>
                     <ul class="dropdown-menu">
                        <li class="not-head">Modo</li>
                        <li>
                           <a  href="#" id="theme-dark">
                              <span class="media-left media-icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-danger"></i><i class="fa fa-moon-o fa-stack-1x fa-inverse"></i></span></span>
                              <div class="media-body" ><span class="block">Oscuro</span></div>
                           </a>
                        </li>
                        <li>
                           <a class="link" href="#" id="theme-light">
                              <span class="media-left media-icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-success"></i><i class="fa fa-sun-o fa-stack-1x fa-inverse"></i></span></span>
                              <div class="media-body"><span class="block">Claro</span></div>
                           </a>
                        </li>
                        <li class="not-footer"></li>
                     </ul>
                  </li>
                  <li class="dropdown notification-menu">
                     <a class="dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-file-text"></i></a>
                     <ul class="dropdown-menu">
                        <li class="not-head">Opciones</li>
                        <li>
                           <a class="link" href="index.php?controller=Cliente&action=getviewFormClientAnalisis&permisos=1,1,1,1">
                              <span class="media-left media-icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-danger"></i><i class="fa fa-group fa-stack-1x fa-inverse"></i></span></span>
                              <div class="media-body"><span class="block">Analizar</span><span class="text-muted block">Clientes Aumento Capital</span></div>
                           </a>
                        </li>
                        <li class="not-footer"></li>
                     </ul>
                  </li>
                  <li class="dropdown notification-menu">
                     <a class="dropdown-toggle link" href="index.php?controller=juegos&action=home" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-home fa-lg"></i></a>
                    
                  </li>
                  <!-- User Menu-->
                  <li class="dropdown">
                     <a class="dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user fa-lg"></i></a>
                     <ul class="dropdown-menu settings-menu">
                        <li><a class ='link'  href="index.php?controller=logout&action=salir"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
                     </ul>
                  </li>
               </ul>
            </div>
         </nav>
      </header>
      <!-- Side-Nav-->
      <aside class="main-sidebar hidden-print">
         <section class="sidebar">
            <div class="user-panel">
               <div class="pull-left image"><?php
                  if($login->imagen == ""){
                      echo"<div class='img-circle' style='background-image: url(assets/img/user_default.jpg)' alt='User Image'></div>";
                  }
                  else{
                      echo"<div class='img-circle image' style='background-image: url(assets/img/fotos_colaborador/$login->imagen)' alt='User Image'></div>";
                  }
                  ?></div>
               <div class="pull-left info">
                  <p>Bienvenid@</p>
                  <p class="designation"><?php $login=$_SESSION["Login_View"];
                     echo strtoupper($login->usuario);
                     ?></p>
               </div>
            </div>
            <!-- Sidebar Menu-->    
            <ul class="sidebar-menu">

               <li class ="treeview">
                  <a href="#"><i class="fa fa-gamepad"></i><span>Juegos</span><i class="fa fa-angle-right"></i></a>
                  <ul class="treeview-menu">
                     <li><a class='link' href="index.php?controller=juegos&action=juegaDiaria"><i class="fa fa-circle-o"></i>Diaria</a></li>
                     <li><a class='link' href="index.php?controller=juegos&action=juega3"><i class="fa fa-circle-o"></i>Juega 3</a></li>
                     <li><a class='link' href="index.php?controller=juegos&action=juegaFecha"><i class="fa fa-circle-o"></i>Fechas</a></li>
                     <li><a class='link' href="index.php?controller=juegos&action=juegaCombo"><i class="fa fa-circle-o"></i>combo</a></li>
                     <li><a class='link' href="index.php?controller=juegos&action=juegaTica"><i class="fa fa-circle-o"></i>Tica</a></li>
                     <li><a class='link' href="index.php?controller=juegos&action=juegaMonazos"><i class="fa fa-circle-o"></i>Monazos</a></li>
                     <li><a class='link' href="index.php?controller=juegos&action=juegaHondurena"><i class="fa fa-circle-o"></i>Hondurena</a></li>
                     <li><a class='link' href="index.php?controller=juegos&action=juegaJ3Honduras"><i class="fa fa-circle-o"></i>J3 Honduras</a></li>
                     <li><a class='link' href="index.php?controller=juegos&action=juegaTerminacion"><i class="fa fa-circle-o"></i>Terminacion 2</a></li>
                     <li><a class='link' href="index.php?controller=juegos&action=juegaPrimera"><i class="fa fa-circle-o"></i>Primera</a></li>
                     <li><a class='link' href="index.php?controller=juegos&action=juegaSalvadorena"><i class="fa fa-circle-o"></i>Salvadorena</a></li>
                     <li><a class='link' href="index.php?controller=juegos&action=juegaMultisorteos"><i class="fa fa-circle-o"></i>Multisorteos</a></li>
                  </ul>
               </li>
               <li ><a class ='link'   href="index.php?controller=Venta&action=index"><i class="fa fa-pie-chart"></i><span>Ventas</span></a></li>
                <li class ="treeview">
                  <a href="#"><i class="fa fa-trophy"></i><span>Ganadores</span><i class="fa fa-angle-right"></i></a>
                  <ul class="treeview-menu">
                     <li><a class ='link'   href="#"><i class="fa fa-circle-o"></i><span>Numeros</span></a></li>
                     <li><a class='link' href="index.php?controller=juegos&action=ver_ganadores"><i class="fa fa-circle-o"></i>Ganadores</a></li>
                  </ul>
               </li>

            </ul>
         </section>
      </aside>
      <div id="page" class="content-wrapper">
      
      </div>
      <!-- Modal -->
      <div class="modal fade" id="avisos" role="dialog">
         <div class="modal-dialog">
            <!-- Modal content-->
            <style>
               .zoom {
               transition: transform .2s;
               }
            </style>
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title"></h4>
               </div>
               <div class="modal-body">
                  <p id="mensaje"></p>
                  <div class = "" >
                     <p ><img id="img" src="" width="100%" height="100%"></p>
                     <!--<iframe width="560" height="315" src="https://www.youtube.com/embed/_EeA1yso2uY" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>-->
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Entendido</button>
               </div>
            </div>
         </div>
      </div>
      <!-- Javascripts-->

      <script
         src="https://code.jquery.com/jquery-3.2.1.min.js"
         integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
         crossorigin="anonymous"></script>
      <!-- script  src="assets/js/jquery-3.0.0.min.js"></script -->
      <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css">
      <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js" integrity="sha256-0YPKAwZP7Mp3ALMRVB2i8GXeEndvCq3eSl/WsAl1Ryk=" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.9.2/jquery.ui.mouse.min.js" integrity="sha512-G0ZcUFkeiDbEYpvL2I6p3pLRoMO3aH/cqAWwN5h2cODGfwk7KsBYcmLf3QWJK85faTBmsrvpbJhku2MRk2mgcw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js" integrity="sha512-0bEtK0USNd96MnO4XhH8jhv3nyRF0eK87pJke6pkYf3cM0uDIhNJy9ltuzqgypoIFXw3JSuiy04tVk4AjpZdZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/1.0.7/push.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
      <script src="assets/js/morris/raphael-2.1.0.min.js"></script>
      <script src="assets/js/bootstrap.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script>
      <script src="assets/js/main.js"></script>
      <script type="text/javascript" src="assets/js/jquery.mask.js"></script>
      <script type="text/javascript" src="assets/js/navegation-main.js"></script>
      <script src="assets/js/validation-send-form.js"></script>
      <script src="assets/js/nofity.js"></script>
      <script src="assets/js/utils.js"></script>
      <!--full calendario-->
      <link rel="stylesheet" href="resources/fullcalendar/css/styles.css">
      <link rel='stylesheet' type='text/css' href='resources/fullcalendar/css/fullcalendar.css' />
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
      <script type='text/javascript' src='resources/fullcalendar/js/moment.min.js'></script>
      <script type='text/javascript' src='resources/fullcalendar/js/fullcalendar.min.js'></script>
      <script type='text/javascript' src='resources/fullcalendar/js/locale/es.js'></script>
      <!--CDN DATATABLE-->
      <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
      <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
      <script src="https://cdn.datatables.net/fixedcolumns/3.2.2/js/dataTables.fixedColumns.min.js"></script>
      <script src="assets/js/dataTables/dataTables.buttons.min.js"  ></script>
      <script src="assets/js/dataTables/buttons.flash.min.js" ></script>
      <script src="assets/js/dataTables/jszip.min.js" ></script>
      <script src="assets/js/dataTables/pdfmake.min.js" ></script>
      <script src="assets/js/dataTables/vfs_fonts.js" ></script>
      <script src="assets/js/dataTables/buttons.html5.min.js" ></script>
      <script src="assets/js/dataTables/buttons.print.min.js" ></script>
      <script src="resources/js/dataTableLenguaje.js" ></script>
      <script src="assets/js/picker/picker.js"></script>
      <script src="assets/js/picker/picker.date.js"></script>
      <script src="assets/js/picker/legacy.js"></script>
      <script src="assets/js/alertifyjs/alertify.min.js"></script>
      <link rel="stylesheet" type="text/css" href="resources/datetimepicker/bootstrap-clockpicker.css ">
      <link rel="stylesheet" type="text/css" href="resources/datetimepicker/jquery-clockpicker.css  ">
      <link rel="stylesheet" type="text/css" href="resources/css/clasificacionprestamocolor.css  ">
      <link rel="stylesheet" type="text/css" href="resources/datetimepicker/standalone.css ">
      <script type="text/javascript" src="resources/datetimepicker/bootstrap-clockpicker.js"></script>
      <script type="text/javascript" src="resources/datetimepicker/jquery-clockpicker.js"></script>
      <script type="text/javascript" src="main.js"></script>
      <!--GEOLOCALIZACION-->
      <script src="https://cdn.jsdelivr.net/npm/ol@v7.3.0/dist/ol.js"></script>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v7.3.0/ol.css">
      <!----- FIRMAS------>
      <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
      </div>
      </div>
   </body>
</html>

<script>
console.log("DOM fully loaded and parsed");
   $("#page").load("index.php?controller=Juegos&action=home");
</script>