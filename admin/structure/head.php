<?php 
  date_default_timezone_set('America/Asuncion'); 
  require_once __DIR__."/../inc/config.php";
?>
<title>Gestor de proyectos</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="WA developers S.R.L.">
<link rel="shortcut icon" href="images/favicon.ico">
<!-- Bootstrap core CSS -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap theme -->
<link href="css/bootstrap-theme.min.css" rel="stylesheet">
<!-- Custom styles for this template -->
<link href="css/theme.css" rel="stylesheet">

<style type="text/css">
    /*Para modificar los style de las publicaciones*/
    html, body {
      /*height: 750px;*/
    }
   
    .banner {
        text-align: center;
        overflow: hidden;
        padding: 0px 0;
        margin-bottom: 0rem;
    }
  
    .banner_footer {
        text-align: center;
        overflow: hidden;
        padding: 0px 0;
        margin-bottom: 0rem;
        display: none;
    }

    .footer {
        position: fixed;
        height: 100px;
        bottom: 0;
        width: 100%;
        z-index: 1030;
    }

    .dropdown-submenu{ position: relative; }
    
    .dropdown-submenu>.dropdown-menu{
      top:0;
      left:100%;
      margin-top:-6px;
      margin-left:-1px;
      -webkit-border-radius:0 6px 6px 6px;
      -moz-border-radius:0 6px 6px 6px;
      border-radius:0 6px 6px 6px;
    }
    
    .dropdown-submenu>a:after{
      display:block;
      content:" ";
      float:right;
      width:0;
      height:0;
      border-color:transparent;
      border-style:solid;
      border-width:5px 0 5px 5px;
      border-left-color:#cccccc;
      margin-top:5px;margin-right:-10px;
    }
    .dropdown-submenu:hover>a:after{
      border-left-color:#555;
    }
    .dropdown-submenu.pull-left{ float: none; }
    .dropdown-submenu.pull-left>.dropdown-menu{
      left: -100%;
      margin-left: 10px;
      -webkit-border-radius: 6px 0 6px 6px;
      -moz-border-radius: 6px 0 6px 6px;
      border-radius: 6px 0 6px 6px;
    }
    @media (max-width: 767.98px) { 
    	.dropdown-submenu.open {
    		background-color: #393a35;
    	}
    	.dropdown-submenu.open>a{
    		  color:#fff !important;
    	}
    }
    a.sucursal {
      <?php
      $color_empresa = datosEmpresas($_SESSION['sfpy_usuario_empresa'])->color;
      if($color_empresa == ''){ $color_empresa = datosEmpresas(1)->color; }
      ?>
      background-color: <?= $color_empresa ?>;
      /*background-color: #99cb32;*/
      /*background-color: #00b899;*/ /*coseres*/
      /*background-color: #fec708;*/ /*guarani*/
      /*background-color: #F05F40;*/ /*quattro*/
      /*background-color: #70cfd2;*/ /*Proinso*/
      /*background-color: #f64558;*/ /*Comodin*/
      border-radius: 5px;
      font-size: 8px;
      font-weight: bold;
      width: 70px;
      height: 33px;
      overflow: hidden;
      display: inline-block;
      margin-top: 9px;
      text-align: center;
      text-decoration: none;
      color: #000;
      float: left;
      display: table;
    }
    
    .select2{
      width: 100% !important;
      margin-bottom: 5px !important;
    }
    .navbar {
      border-radius: 0 !important;
    }
</style>
<!-- <script type="text/javascript" src="js/name.js"> </script> -->
<script type="text/javascript">
  // 1: VALORES ENTEROS // 2: VALORES DECIMALES
  var REGION = <?=TIPO?>;
  var MONEDA = '<?=MONEDA?>';
  //setInterval(function(){$.post("inc/sesion.php")}, 500);
  setInterval(function(){ 
      var xhttp = new XMLHttpRequest();
      xhttp.open("POST", "inc/sesion.php", true);
      xhttp.send();
  }, 5000);
</script>
<link rel="stylesheet" defer async href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">