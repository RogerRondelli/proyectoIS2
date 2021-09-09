<?php
	include ("inc/funciones.php");
	// un comentario
	//$pag = basename($_SERVER['PHP_SELF']);
	verificaLogin();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include_once "structure/head.php"; ?>

	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<style type="text/css">
		.ui-corner-all{
			font-size:12px;
			z-index:20000;
		}
		table {
			border-collapse: collapse;
		}

		td {
			padding-top: .2em;
			padding-bottom: .2em;
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
						<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">Agregar Rol</button>
					</div>
					<div class="col-md-9" id="mensaje"></div>

					<div class="col-md-12" style="margin-top:15px">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Nombre del Rol</th>
									<!-- <th>Nivel de Acceso</th> -->
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
									<td align="right" class="labelmodal">Rol:</td>
									<td align="left"><input type="text" class="input_medium" id="rol" autocomplete="off" required></td>
								</tr>
								<tr style="display:none">
									<td align="right" class="labelmodal">Nivel de Acceso:</td>
									<td align="left">
										<select class="input_medium" id="acceso">
											<option value="0" selected>Total</option>
											<option value="1">Semi-Total</option>
											<option value="2">Local y Sublocales</option>
											<option value="3">Local</option>
										</select>
									</td>
								</tr>

								<tr>
									<td id="msjRegistro" colspan="2"><br></td>
								</tr>
							</table>
						</div>

						<!-- Modal footer -->
						<div class="modal-footer">
							<button type="button" class="btn btn-success btn-sm" id="guardar_registro">Agregar Usuario</button>
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
							<h4 class="modal-title">Editar Rol</h4>
						</div>

						<!-- Modal body --> 
						<div class="modal-body">
							<table style="width:490; margin:15px auto 0 auto">
								<tr>
									<td align="right" class="labelmodal">Rol:</td>
									<td align="left">
										<input type="hidden" id="id_rol">
										<input class="input_medium" id="rol_editar" autocomplete="off" required>
									</td>
								</tr>
								<tr style="display:none">
									<td align="right" class="labelmodal">Nivel de Acceso:</td>
									<td align="left">
										<select class="input_medium" id="acceso_editar">
											<option value="0" selected>Total</option>
											<option value="1">Semi-Total</option>
											<option value="2">Local y Sublocales</option>
											<option value="3">Local</option>
										</select>
									</td>
								</tr>
								<tr>
									<td><br></td>
								</tr>
							</table>
						</div>

						<!-- Modal footer -->
						<div class="modal-footer">
							<button type="button" class="btn btn-success btn-sm" id="guardar_editar">Editar Rol</button>
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
			<div class="modal-body" id="titulo_eliminar"></div>
			<div class="modal-footer">
				<div id="mensaje_eliminar" style="float:left"></div>
				<input type="hidden" id="nombre_borrar">
				<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-danger btn-sm" id="confirm_borrado" onclick="confirmarBorrado()">Eliminar</button>
			</div>
		</div>
	  </div>
	</div>

	<script type="text/javascript">
		var roles;
        $(document).ready(function () {
			tabla();

            $("#guardar_editar").click(function (event) {
				let data = {
					id_rol: $('#id_rol').val(),
					rol: $('#rol_editar').val(),
					acceso: $('#acceso_editar option:selected').val()
				};

				$.ajax({
					dataType: 'json',
					async: false,
					url: '../server/public/api/roles/edit',
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
				let rol = $('#rol').val(),
					acceso = $('#acceso').val();

				if (rol == '') {
					$("#msjRegistro").html(alertDismissJS("Escriba el nombre del rol", 'error'));
					return false
				}
				// console.log(rol,acceso)
				// return
				$.ajax({
                    dataType: 'html',
					async: false,
					url: '../server/public/api/roles/create',
                    type: 'POST', 
                    data: {rol: rol,acceso:acceso},
                    success: function (data, status, xhr) {
						console.log(data);
						location.reload();
                    },
					error: function (xhr) {
						$("#msjRegistro").html(alertDismissJS("No se pudo completar la operación: " + xhr.status + " " + xhr.statusText, 'error'));
					}
                });
			});
        });

		function tabla(){
			let html = '';
			$.ajax({
				dataType: 'json',
				async: false,
				url: '../server/public/api/roles/list',
				type: 'GET',
				success: function (data, status, xhr) {
					console.log(data.data.length)
					let datos = data.data;
					roles = datos;
					for (let index = 0; index < datos.length; index++) {
						let element = datos[index];
						html += `<tr>
									<td>`+element['rol']+`</td>
									<td>
										<div style="text-align:center;font-size:18px;margin-top:10px"> 
											<span class="glyphicon glyphicon-pencil mouse-pointer btn-md btn-grid" style="cursor:pointer" title="Editar"  onclick="editar(`+element['id_rol']+`)" ></span>
											<span class="glyphicon glyphicon-trash mouse-pointer btn-md btn-grid" style="cursor:pointer" onclick="preguntaBorrado(`+element['id_rol']+`)" title="Borrar"></span>
										</div>
									</td>
								</tr>`
					}
					$('#tableBody').html(html);
				},
				error: function (xhr) {
					console.log(xhr)
					$("#mensaje").html(alertDismissJS("No se pudo completar la operación: " + xhr.status + " " + xhr.statusText, 'error'));
				}
			});
		}

		function editar(id) {
			for (let i = 0; i < roles.length; i++) {
				if (id == roles[i]['id_rol']) {
					let element = roles[i];
					$("#rol_editar").val(element.rol);
					$("#acceso_editar").val(element.id_acceso);
					$("#id_rol").val(element.id_rol);
				}
			}
			$('#modalEditar').modal();
		}

		function preguntaBorrado(id){
			$('.modal-eliminar').modal();
			$("#id_eliminar").val(id);
			$("#titulo_eliminar").html("¿Desea eliminar el rol?");
		}

		function confirmarBorrado(){
			let id_rol = $("#id_eliminar").val();

			$.ajax({
				dataType: 'html',
				type: 'POST',
				url: '../server/public/api/roles/delete',
				cache: false,
				data: {id_rol: id_rol},
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
