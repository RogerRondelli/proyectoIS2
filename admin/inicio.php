<?php
  include ("inc/funciones.php");
  include ("structure/name.php");
  //$pag = basename($_SERVER['PHP_SELF']);
  verificaLogin();
  setlocale(LC_ALL,"es_ES");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
   <?php include_once "structure/head.php"; ?>

    <!-- Date picker -->
    <script src="js/jquery-1.10.2.min.js"></script>
    <link rel="stylesheet" type="text/css" media="all" href="css/daterangepicker-bs3.css" />
    <script type="text/javascript" src="js/moment.js"></script>
  	<script type="text/javascript" src="js/daterangepicker.js"></script>
	<style>
		#reportrange {
		color: #333333;
		padding: 8px;
		line-height: 18px;
		cursor: pointer;
		}
		#reportrange .caret {
			margin-top: 1px;
			margin-left: 2px;
		}
		#reportrange span {
			padding-left: 3px;
		}
	
		#ocultar{
			float:right;
		}
	</style>

	<style type="text/css">
		panel-body-ventas {
			padding: 5px
		}
		@media print {
			/*.row{ display: none; }*/
			.imprimir{ display: none; }
			/*#print{ display: none; }*/

		}
		

    </style>
    <script type="text/javascript" src="js/funciones.js?v<?=$version_js?>"></script>
  </head>

  <body>
	<?php include_once "structure/navbar.php" ?>
    <div id="wrap">
      <div class="container">
        <div class="page-header">
          <h2>
          	Modulos
            <small>
			<div id="reportrange" style="cursor: pointer; display: inline-block">
				<span></span> 
			</div>
			</small>
		  </h2>
        </div>
		<?php echo menu($_SESSION['sfpy_usuario'],2); ?>
      </div> 
    </div>

    <?php echo piePagina(); ?>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>