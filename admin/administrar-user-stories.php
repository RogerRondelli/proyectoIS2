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
	<link href="css/select2.min.css" rel="stylesheet">
	<script type="text/javascript" src="js/select2.min.js"></script>
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
						<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">Agregar User Storie</button>
					</div>
					<div class="col-md-9" id="mensaje"></div>

					<div class="col-md-12" style="margin-top:15px">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Descripcion</th>
									<th>Backlog</th>
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
							<h4 class="modal-title">Agregar User Storie</h4>
						</div>

						<!-- Modal body --> 
						<div class="modal-body">
							<table style="width:490px; margin:15px auto 0 auto">
								<tr>
									<td align="right" class="labelmodal">Descripcion:</td>
									<td align="left"><textarea id="descripcion" name="descripcion" style="width: -webkit-fill-available;"></textarea></td>
								</tr>
								<!-- <tr>
									<td align="right" class="labelmodal">Estado:</td>
									<td align="left">
										<select class="input_medium" id="estado">
											<option value="Abierto">Abierto</option>
											<option value="Cerrado">Cerrado</option>
										</select>
									</td>
								</tr> -->
								<tr>
									<td align="right" class="labelmodal">Backlog:</td>
									<td align="left">
										<select class="input_medium" id="id_backlog">
											<option value=""> - </option>
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
							<button type="button" class="btn btn-success btn-sm" onclick="guardar()">Guardar</button>
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
							<h4 class="modal-title">Editar User Storie</h4>
						</div>

						<!-- Modal body --> 
						<div class="modal-body">
							<table style="width:490px; margin:15px auto 0 auto">
								<tr>
									<td align="right" class="labelmodal">Nombre:</td>
                                    <input type="hidden" id="id_user_storie">
                                    <td align="left"><textarea id="descripcion_editar" name="descripcion_editar" style="width:-webkit-fill-available;"></textarea></td>
								</tr>
								<!-- <tr>
									<td align="right" class="labelmodal">Estado:</td>
									<td align="left">
										<select class="input_medium" id="estado_editar">
                                            <option value="Abierto">Abierto</option>
											<option value="Cerrado">Cerrado</option>
										</select>
									</td>
								</tr> -->
								<tr>
									<td align="right" class="labelmodal">Backlog:</td>
									<td align="left">
										<select class="input_medium" id="id_backlog_editar">
											<option value=""> - </option>
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
							<button type="button" class="btn btn-success btn-sm" onclick="guardarEditar()">Editar</button>
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
		var backlogs;
        $(document).ready(function () {
			tabla();
        });
		
		var backlogs;
		function tabla(){
			let html = '',
				options = '<option value=""> - </option>';
			$.ajax({
				dataType: 'json',
				async: false,
				url: '../server/public/api/userstories/list',
				type: 'GET',
				success: function (data, status, xhr) {
					console.log(data)
					let user_stories = data.user_stories;
					backlogs = data.backlogs;
					for (let index = 0; index < user_stories.length; index++) {
						let element = user_stories[index];
						html += `<tr>
									<td>`+element['descripcion']+`</td>
									<td>`+element['nombre']+`</td>
									<td>
										<div style="text-align:center;font-size:18px;margin-top:10px"> 
											<span class="glyphicon glyphicon-pencil mouse-pointer btn-md btn-grid" style="cursor:pointer" title="Editar" onclick="editar(`+element['id_user_storie']+`)" ></span>
											<span class="glyphicon glyphicon-trash mouse-pointer btn-md btn-grid" style="cursor:pointer" onclick="preguntaBorrado(`+element['id_user_storie']+`)" title="Borrar"></span>
										</div>
									</td>
								</tr>`
					}

					for (let index = 0; index < backlogs.length; index++) {
						let element = backlogs[index];
						options += `<option value="`+element.id_backlog+`"> `+element.nombre+` </option>`;
					}

					$('#tableBody').html(html);
					$('#id_backlog').html(options);
					lista_usuario(usuarios)
				},
				error: function (xhr) {
					console.log(xhr)
					$("#mensaje").html(alertDismissJS("No se pudo completar la operación: " + xhr.status + " " + xhr.statusText, 'error'));
				}

			});
		}

		function guardar(){
			let datas = {
				descripcion: $('#descripcion').val(),
				// estado: $('#estado').val(),
				id_backlog: $('#id_backlog').val()
			} 

			if (descripcion == '') {
				$("#msjRegistro").html(alertDismissJS("Escriba la descripcion del user storie", 'error'));
				return false
			}
			console.log(datas)
			// return false
			$.ajax({
				dataType: 'html',
				async: false,
				url: '../server/public/api/userstories/create',
				type: 'POST', 
				data: datas,
				success: function (data, status, xhr) {
					console.log(data);
					location.reload();
				},
				error: function (xhr) {
					$("#msjRegistro").html(alertDismissJS("No se pudo completar la operación: " + xhr.status + " " + xhr.statusText, 'error'));
				}
			});
		}

		function guardarEditar(){
			let data = {
				id_backlog: $('#id_backlog').val(),
				nombre: $('#nombre_editar').val(),
				id_proyecto: $('#id_proyecto_editar').val(),
				estado: $('#estado_editar').val()
			};

			console.log(data)
			// return false
			
			$.ajax({
				dataType: 'json',
				async: false,
				url: '../server/public/api/userstories/edit',
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
		}

		function eliminarDuplicados(arrayIn) {
			var arrayOut = [];
			arrayOut.push(arrayIn[0]);

			arrayIn.forEach(item=> {
				var exists = arrayOut.filter(x=>x.id_usuario == item.id_usuario)
				if(exists.length == 0) arrayOut.push(item);
			})

			return arrayOut;
		}

		function lista_usuario(data){
			let lista = eliminarDuplicados(data)
			let options = '<option value=""> - </option>';
			for (let index = 0; index < lista.length; index++) {
				let element = lista[index];
				let nombre_apellido = element['nombre'] + element['apellido'];
				options += `<option value="`+element['id_usuario']+`">`+nombre_apellido+`</option>`;
			}
			
			$('#id_usuario,#id_usuario_editar').html(options)
		}

		function editar(id) {
			$.ajax({
				dataType: 'json',
				async: false,
				url: '../server/public/api/userstorie/list',
				type: 'POST', 
				data: {id:id},
				success: function (data, status, xhr) {
					console.log(data,backlogs);
					let user_stories = data.user_stories[0];

					$("#descripcion_editar").val(user_stories.descripcion);
					$("#id_backlog").val(user_stories.id_backlog);
					$("#id_user_storie").val(user_stories.id_user_storie);

					let options = '<option> - </option>';
					for(let k=0;k < backlogs.length; k++){
						let element = backlogs[k];
						let selected = '';

						if(element.id_backlog == user_stories.id_backlog){
							selected = 'selected';
						}

						console.log(element.id_backlog,user_stories.id_backlog)
						options += '<option '+selected+' value="'+element.id_backlog+'">'+element.nombre+'</option>'
					}

					$("#id_backlog_editar").html(options);
					$('#modalEditar').modal();
				},
				error: function (xhr) {
					$("#msjRegistro").html(alertDismissJS("No se pudo completar la operación: " + xhr.status + " " + xhr.statusText, 'error'));
				}
			});

		}

		function preguntaBorrado(id){
			$('.modal-eliminar').modal();
			$("#id_eliminar").val(id);
			$("#titulo_eliminar").html("¿Desea eliminar el proyecto?");
		}

		function confirmarBorrado(){
			let id_backlog = $("#id_eliminar").val();

			$.ajax({
				dataType: 'html',
				type: 'POST',
				url: '../server/public/api/userstories/delete',
				cache: false,
				data: {id_backlog: id_backlog},
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
