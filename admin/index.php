<?php
  // include_once __DIR__ .'/csrfp/libs/csrf/csrfprotector.php';
  //Initialise CSRFGuard library
  // csrfProtector::init();
  include ("inc/funciones.php");
  session_start();
  if(isset($_SESSION['sfpy_usuario'])){
    header('Location:inicio.php');
  }else if(isset($_COOKIE['sfpy_recordar_usuario'])){
    $_SESSION['sfpy_usuario'] = $_COOKIE['sfpy_recordar_usuario'];
    header('Location:inicio.php');
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="images/favicon.ico">

    <title><?php echo "Administraci&oacute;n - ".nombreEmpresa(); ?></title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/theme.css" rel="stylesheet">

    <script src="js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript">
  
      function iniciarSesion(r){
        var u = $('#input_u').val(),
            p = $('#input_p').val();

        $.ajax({
          async: true,
          type: 'POST',
          dataType: 'json',
          data: {usu: u, password: p},
          url: '../server/public/api/users/signin',
          beforeSend: function (datos){
            $('#mensaje').html(alertDismissJS("Accediendo...","yellow"));
          },
          success: function (datos) {
            console.log(datos)
            // return
            if (datos.code == 200){
              $.ajax({
                type: 'POST',
                dataType: 'json',
                data: {id_usuario: datos.data.id_usuario,
                      nombre_usuario: datos.data.nombre_usuario,
                      rol: datos.data.rol},
                url: './login.php',
                success: function (datos) {
                  console.log('asdasdadss')
                }
              });

              window.location.href = 'inicio.php'
            }else{
              $('#mensaje').html(datos.mensaje);
            }
          },
          error: function (xhr){
            $('#mensaje').html(alertDismissJS("No se pudo completar la operación. " + xhr.status + " " + xhr.statusText, 'error'));
          }
        });
      }
    </script>
  </head>

  <body>
    <div id="wrap">
      <div class="container">
        <!-- Main component for a primary marketing message or call to action -->
        <div class="login">
          <span><div id="mensaje"></div></span>
          <form class="form-signin" role="form">
            <!-- <a href=""><img src="images/ima.svg" class="img-responsive center-block" alt="Responsive image"></a>
            <br> -->
            <h3 class="form-signin-heading" align="center" >Control de Acceso</h3>
            <input type="text" id="input_u" class="form-control" placeholder="Nombre de usuario" required autofocus>
            <input type="password" id="input_p" class="form-control" placeholder="Contraseña" onchange="javascript:enterClick(this.id, 'iniciarButton')" required>
            <!-- <label class="checkbox">
              <input type="checkbox" value="rememberme" id="rememberme">Mantener sesión iniciada
            </label> -->
            <button class="btn btn-lg btn-primary btn-block" type="button" id="iniciarButton" onclick="iniciarSesion($('#rememberme').is(':checked'))">Iniciar</button>
            <!-- <span class="text-center">Usuario admin / Contraseña 12345</span> -->
          </form>
        </div>
      </div> <!-- /container -->
    </div>
    <?php echo piePagina(); ?>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/menuHover.js"></script>
    <script src="js/funciones.js?v<?=$version_js?>"></script>
  </body>
</html>