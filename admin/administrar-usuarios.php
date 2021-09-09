<?php
	include ("inc/funciones.php");
	//$pag = basename($_SERVER['PHP_SELF']);
	verificaLogin();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
	<?php include_once "structure/head.php"; ?>

	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>

	<style type="text/css">
		table {
			border-collapse: collapse;
		}

		td {
			padding-top: .2em;
			padding-bottom: .2em;
		}

		.btn-grid{
			width:50%;
			font-size:90%;
		}

		.labelmodal {
			padding-right: 10px;
			font-weight: bold;
		}
	</style>
  </head>

  <body>
	<!-- Fixed navbar -->
	<?php include_once "structure/navbar.php" ?>
	<!-- Wrap all page content here -->
	<div id="wrap">
		<div class="container">
			<div class="page-header">
				<h2><?php echo nombrePagina(basename($_SERVER['PHP_SELF'])); ?></h2>
			</div>
			
			<div class="row">
				<div class="col-md-12">
					<div class="col-md-3"  style="padding-left:0">
						<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">Registrar Usuario</button>
					</div>
					<div class="col-md-9" id="mensaje"></div>

					<div class="col-md-12" style="margin-top:15px">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Username</th>
									<th>Nombre</th>
									<th>Apellido</th>
									<th>Rol</th>
									<th>Estado</th>
									<th>Acciones</th>
								</tr>
							</thead>
							<tbody id="tableBody"></tbody>
						</table>
					</div>
				</div>
				<!-- The Modal -->
				<div class="modal" id="myModal">
					<div class="modal-dialog">
						<div class="modal-content">

						<!-- Modal Header -->
						<div class="modal-header">
							<h4 class="modal-title">Registrar Usuario</h4>
						</div>

						<!-- Modal body --> 
						<div class="modal-body">
							<table style="width:490; margin:15px auto 0 auto">
								<tr>
									<td align="right" class="labelmodal">Nombre:</td>
									<td align="left"><input type="text" class="input_medium" id="nombre" autocomplete="off" required></td>
								</tr>
								<tr>
									<td align="right" class="labelmodal">Apellido:</td>
									<td align="left"><input type="text" class="input_medium" id="apellido" autocomplete="off" required></td>
								</tr>
								<tr>
									<td align="right" class="labelmodal">Nombre de Usuario:</td>
									<td align="left"><input type="text" class="input_medium" id="usuario" autocomplete="off" required></td>
								</tr>
								<tr>
									<td align="right" class="labelmodal">Contraseña:</td>
									<td align="left"><input class="input_medium" type="password" id="pass" autocomplete="off" required></td>
								</tr>
								<tr>
									<td align="right" class="labelmodal">Confirmar Contraseña:</td>
									<td align="left"><input class="input_medium" type="password" id="pass2" autocomplete="off" required></td>
								</tr>
								<tr>
									<td align="right" class="labelmodal">Rol:</td>
									<td align="left"><select class="form-select" id="selectRol"></select></td>
								</tr>
								<tr>
									<td id="msjRegistro" colspan="2"><br></td>
								</tr>
							</table>
						</div>

						<!-- Modal footer -->
						<div class="modal-footer">
							<button type="button" class="btn btn-success btn-sm" id="guardar_registro">Registrar Usuario</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
						</div>

						</div>
					</div>
				</div>

				<div class="modal" id="modalEditar">
					<div class="modal-dialog">
						<div class="modal-content">

						<!-- Modal Header -->
						<div class="modal-header">
							<h4 class="modal-title">Editar Usuario</h4>
						</div>

						<!-- Modal body --> 
						<div class="modal-body">
							<table style="width:490; margin:15px auto 0 auto">
								<tr>
									<td align="right" class="labelmodal">Nombre:</td>
									<td align="left">
										<input type="hidden" id="id_usuario">
										<input type="text" class="input_medium" id="nombre_editar" autocomplete="off" required>
									</td>
								</tr>
								<tr>
									<td align="right" class="labelmodal">Apellido:</td>
									<td align="left"><input type="text" class="input_medium" id="apellido_editar" autocomplete="off" required></td>
								</tr>
								<tr>
									<td align="right" class="labelmodal">Nombre de Usuario:</td>
									<td align="left"><input type="text" class="input_medium" id="usuario_editar" autocomplete="off" required></td>
								</tr>
								<tr>
									<td align="right" class="labelmodal">Contraseña:</td>
									<td align="left"><input class="input_medium" type="password" id="pass_editar" autocomplete="off" required></td>
								</tr>
								<tr>
									<td align="right" class="labelmodal">Confirmar Contraseña:</td>
									<td align="left"><input class="input_medium" type="password" id="pass2_editar" autocomplete="off" required></td>
								</tr>
								<tr>
									<td align="right" class="labelmodal">Rol:</td>
									<td align="left"><select class="form-select" id="selectRol_editar"></select></td>
								</tr>
								<tr>
									<td id="msjRegistro" colspan="2"><br></td>
								</tr>
							</table>
						</div>

						<!-- Modal footer -->
						<div class="modal-footer">
							<button type="button" class="btn btn-success btn-sm" id="editar_usuario">Editar Usuario</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
						</div>

						</div>
					</div>
				</div>
			</div>
		</div> <!-- /container -->
	</div> <!-- /wrap -->
	<?php echo piePagina(); ?>

	<!-- MODAL ELIMINAR -->
	<div class="modal modal-eliminar" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
				<h4 id="mySmallModalLabel" class="modal-title">Eliminar<a href="#mySmallModalLabel" class="anchorjs-link"><span class="anchorjs-icon"></span></a></h4>
			</div>
			<input type="hidden" id="id_eliminar" name="">
			<div class="modal-body" id="titulo_eliminar">

				&nbsp;
			</div>
			<div class="modal-footer">
				<div id="mensaje_eliminar" style="float:left"></div>
				<input type="hidden" id="nombre_borrar">
				<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-danger btn-sm" onclick="confirmarBorrado()">Eliminar</button>
			</div>
		</div>
	  </div>
	</div>

	<script type="text/javascript">
		var usuarios, roles;

		$(document).ready(function () {
			tabla();
			rol();
			// update the edited row when the user clicks the 'Save' button.
			$("#editar_usuario").click(function (event) {
				let	password = $('#pass_editar').val(),
					rePassword = $('#pass2_editar').val();

				if(password != rePassword){
					alert('Las contraseñas no coinciden');
					return false;
				}

				let data = {
					id_usuario: $('#id_usuario').val(),
					nombre: $('#nombre_editar').val(),
					apellido: $('#apellido_editar').val(),
					usuario: $('#usuario_editar').val(),
					rol: $('#selectRol_editar').val(),
					password: $('#pass_editar').val()
				};

				$.ajax({
					dataType: 'json',
					async: false,
					url: '../server/public/api/users/edit',
					type: 'POST',
					data: data,
					success: function (data, status, xhr) {
						console.log(data.data.length)
						location.reload();
					},
					error: function (xhr) {
						$("#mensaje").html(alertDismissJS("No se pudo completar la operación: " + xhr.status + " " + xhr.statusText, 'error'));
					}

				});
			});

			$("#guardar_registro").click(function (event) {
				$i=0;
				var nombre = $('#nombre').val();
				$('select,input,div').css('border', '');
				if (nombre =='') {
					$('#nombre').css('border', 'solid 1px red');
					$i=1;
				}
				var apellido = $('#apellido').val();
				if (apellido=='') {
					$('#apellido').css('border', 'solid 1px red');
					$i=1;
				}
				var usuario = $('#usuario').val();
				if (usuario=='') {
					$('#usuario').css('border', 'solid 1px red');
					$i=1;
				}
				var password = $('#pass').val();
				if (password=='') {
					$('#pass').css('border', 'solid 1px red');
					$i=1;
				}
				var password2 = $('#pass2').val();
				var rol = $('#selectRol').val();
				if (rol=='') {
					$('#rol').css('border', 'solid 1px red');
					$i=1;
				}
				// console.log(nombre, apellido, usuario, rol, password)
				// return false;
				if ($i==0) {
					if (password !=password2) {
						$('#pass').css('border', 'solid 1px red');
						$('#pass2').css('border', 'solid 1px red');
						$("#msjRegistro").html(alertDismissJS("No coinciden las contrase&ntilde;as.", 'error'));
						return false
					}
					$.ajax({
						dataType: 'html',
						async: false,
						url: '../server/public/api/users/create',
						type: 'POST',
						data: {nombre: nombre, apellido: apellido, usuario: usuario, password: password, rol: rol},
						success: function (data, status, xhr) {
							console.log(data)
							location.reload();
						},
						error: function (xhr) {
							$("#msjRegistro").html(alertDismissJS("No se pudo completar la operación: " + xhr.status + " " + xhr.statusText, 'error'));
						}

					});
				}else{
					$("#msjRegistro").html(alertDismissJS("Complete los campos en rojo", 'error'));
				}

			});
		});

		function tabla(){
			let html = '';
			$.ajax({
				dataType: 'json',
				async: false,
				url: '../server/public/api/users/list',
				type: 'GET',
				success: function (data, status, xhr) {
					console.log(data.data.length)
					let datos = data.data;
					usuarios = datos;
					for (let index = 0; index < datos.length; index++) {
						let element = datos[index];
						html += `<tr>
									<td>`+element['nombre_usuario']+`</td>
									<td>`+element['nombre']+`</td>
									<td>`+element['apellido']+`</td>
									<td>`+element['rol']+`</td>
									<td>`+element['estado']+`</td>
									<td>
										<div style="text-align:center;font-size:18px;margin-top:10px"> 
											<span class="glyphicon glyphicon-pencil mouse-pointer btn-md btn-grid" style="cursor:pointer" title="Editar"  onclick="editar(`+element['id_usuario']+`)" ></span>
											<span class="glyphicon glyphicon-trash mouse-pointer btn-md btn-grid" style="cursor:pointer" onclick="preguntaBorrado(`+element['id_usuario']+`)" title="Borrar"></span>
										</div>
									</td>
								</tr>`
					}
					$('#tableBody').html(html);
				},
				error: function (xhr) {
					$("#mensaje").html(alertDismissJS("No se pudo completar la operación: " + xhr.status + " " + xhr.statusText, 'error'));
				}

			});
		}

		function rol(){
			let html = '';
			$.ajax({
				dataType: 'json',
				async: false,
				url: '../server/public/api/users/roles',
				type: 'GET',
				success: function (data, status, xhr) {
					console.log(data)
					let datos = data.data;
					roles = datos;
					for (let index = 0; index < datos.length; index++) {
						let element = datos[index];
						html += `<option value="`+element['id_rol']+`">`+element['rol']+`</option>`;
					}
					$('#selectRol').html(html);
					$('#selectRol_editar').html(html);
				},
				error: function (xhr) {
					$("#mensaje").html(alertDismissJS("No se pudo completar la operación: " + xhr.status + " " + xhr.statusText, 'error'));
				}

			});
		}

		function editar(id) {
			for (let i = 0; i < usuarios.length; i++) {
				if (id == usuarios[i]['id_usuario']) {
					let element = usuarios[i];
					$("#id_usuario").val(element.id_usuario);
					$("#nombre_editar").val(element.nombre);
					$("#apellido_editar").val(element.apellido);
					$("#usuario_editar").val(element.nombre_usuario);
					$("#selectRol_editar").val(element.id_rol);
				}
			}
			$('#modalEditar').modal();
		}

		//ELIMINAR
		function preguntaBorrado(id){
			$('.modal-eliminar').modal();
			$("#id_eliminar").val(id);
			$("#titulo_eliminar").html("¿Desea eliminar el registro?");
		}

		function confirmarBorrado(){
			$.ajax({
				dataType: 'html',
				type: 'POST',
				url: '../server/public/api/users/delete',
				cache: false,
				data: {id_usuario: $("#id_eliminar").val()},
				beforeSend: function(){
					$("#mensaje_eliminar").html("<img src='images/progress_bar.gif'>");
				},
				success: function (data, status, xhr) {
					console.log(data)
					location.reload();
				},
				error: function (xhr) {
					$("#mensaje_eliminar").html(alertDismissJS("No se pudo completar la operación: " + xhr.status + " " + xhr.statusText, 'error'));
				}
			});
		}


	</script>

	<!-- Bootstrap core JavaScript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="js/bootstrap.min.js"></script>
	<!-- <script src="js/menuHover.js"></script> -->
	<script src="js/funciones.js?v<?=$version_js?>"></script>
  </body>
</html>
