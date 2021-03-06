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
						<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">Agregar Backlog</button>
					</div>
					<div class="col-md-9" id="mensaje"></div>

					<div class="col-md-12" style="margin-top:15px">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Nombre</th>
									<th>Estado</th>
									<th>Proyecto</th>
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
							<h4 class="modal-title">Agregar Backlog</h4>
						</div>

						<!-- Modal body --> 
						<div class="modal-body">
							<table style="width:490; margin:15px auto 0 auto">
								<tr>
									<td align="right" class="labelmodal">Nombre:</td>
									<td align="left"><input type="text" class="input_medium" id="nombre" autocomplete="off" required></td>
								</tr>
								<tr>
									<td align="right" class="labelmodal">Estado:</td>
									<td align="left">
										<select class="input_medium" id="estado">
											<option value="Abierto">Abierto</option>
											<option value="Cerrado">Cerrado</option>
										</select>
									</td>
								</tr>
								<tr>
									<td align="right" class="labelmodal">Proyecto:</td>
									<td align="left">
										<select class="input_medium" id="id_proyecto">
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
							<h4 class="modal-title">Editar Backlog</h4>
						</div>

						<!-- Modal body --> 
						<div class="modal-body">
							<table style="width:490; margin:15px auto 0 auto">
								<tr>
									<td align="right" class="labelmodal">Nombre:</td>
									<td align="left">
										<input type="hidden" id="id_backlog">
										<input class="input_medium" id="nombre_editar" autocomplete="off" required>
									</td>
								</tr>
								<tr>
									<td align="right" class="labelmodal">Estado:</td>
									<td align="left">
										<select class="input_medium" id="estado_editar">
                                            <option value="Abierto">Abierto</option>
											<option value="Cerrado">Cerrado</option>
										</select>
									</td>
								</tr>
								<tr>
									<td align="right" class="labelmodal">Proyectos:</td>
									<td align="left">
										<select class="input_medium" id="id_proyecto_editar">
											<option value=""> - </option>
										</select>
									</td>
								</tr>
								<tr>
									<td id="msjRegistroeditar" colspan="2"><br></td>
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
				<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">??</span></button>
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

            $("#guardar_editar").click(function (event) {
				let data = {
					id_proyecto: $('#id_proyecto').val(),
					nombre: $('#nombre_editar').val(),
					id_proyecto: $('#id_proyecto_editar').val(),
					estado: $('#estado_editar option:selected').val()
				};

				console.log(data)
				// return false
               
				$.ajax({
					dataType: 'json',
					async: false,
					url: '../server/public/api/backlogs/edit',
					type: 'POST',
					data: data,
					success: function (data, status, xhr) {
						console.log(data.data.length)
						location.reload();
					},
					error: function (xhr) {
						$("#mensaje").html(alertDismissJS("No se pudo completar la operaci??n: " + xhr.status + " " + xhr.statusText, 'error'));
					}

				});
            });
			
			$("#guardar_registro").click(function (event) {
				let datas = {
					nombre: $('#nombre').val(),
					estado: $('#estado').val(),
					id_proyecto: $('#id_proyecto').val()
				} 

				if (nombre == '') {
					$("#msjRegistro").html(alertDismissJS("Escriba el nombre del proyecto", 'error'));
					return false
				}
				console.log(datas)
				return false
				$.ajax({
                    dataType: 'html',
					async: false,
					url: '../server/public/api/backlogs/create',
                    type: 'POST', 
                    data: datas,
                    success: function (data, status, xhr) {
						console.log(data);
						location.reload();
                    },
					error: function (xhr) {
						$("#msjRegistro").html(alertDismissJS("No se pudo completar la operaci??n: " + xhr.status + " " + xhr.statusText, 'error'));
					}
                });
			});
        });
		
		var proyectos;
		function tabla(){
			let html = '',
				options = '<option value=""> - </option>';
			$.ajax({
				dataType: 'json',
				async: false,
				url: '../server/public/api/backlogs/list',
				type: 'GET',
				success: function (data, status, xhr) {
					console.log(data)
					let datos = data.data;
					proyectos = data.proyectos;
					usuarios = data.usuarios;
					backlogs = datos;
					for (let index = 0; index < datos.length; index++) {
						let element = datos[index];
						html += `<tr>
									<td>`+element['nombre']+`</td>
									<td>`+element['estado']+`</td>
									<td>`+element['nombre_proyecto']+`</td>
									<td>
										<div style="text-align:center;font-size:18px;margin-top:10px"> 
											<span class="glyphicon glyphicon-pencil mouse-pointer btn-md btn-grid" style="cursor:pointer" title="Editar" onclick="editar(`+element['id_backlog']+`)" ></span>
											<span class="glyphicon glyphicon-trash mouse-pointer btn-md btn-grid" style="cursor:pointer" onclick="preguntaBorrado(`+element['id_backlog']+`)" title="Borrar"></span>
										</div>
									</td>
								</tr>`
					}

					for (let index = 0; index < proyectos.length; index++) {
						let element = proyectos[index];
						options += `<option value="`+element.id_proyecto+`"> `+element.nombre+` </option>`;
					}

					$('#tableBody').html(html);
					$('#id_proyecto').html(options);
					lista_usuario(usuarios)
				},
				error: function (xhr) {
					console.log(xhr)
					$("#mensaje").html(alertDismissJS("No se pudo completar la operaci??n: " + xhr.status + " " + xhr.statusText, 'error'));
				}

			});
		}

		function verificar(data,editar = ''){
			if (data.nombre == '') {
				$("#msjRegistro"+editar).html(alertDismissJS("Escriba el nombre del backlog", 'error'));
				return false
			}

			if (data.id_proyecto == '' || data.id_proyecto == '-') {
				$("#msjRegistro"+editar).html(alertDismissJS("Debe seleccionar un proyecto", 'error'));
				return false
			}
		}

		function guardar(){
			let datas = {
				nombre: $('#nombre').val(),
				estado: $('#estado').val(),
				id_proyecto: $('#id_proyecto').val()
			} 

			verificar(datas)

			console.log(datas)
			// return false
			$.ajax({
				dataType: 'html',
				async: false,
				url: '../server/public/api/backlogs/create',
				type: 'POST', 
				data: datas,
				success: function (data, status, xhr) {
					console.log(data);
					location.reload();
				},
				error: function (xhr) {
					$("#msjRegistro").html(alertDismissJS("No se pudo completar la operaci??n: " + xhr.status + " " + xhr.statusText, 'error'));
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

			verificar(data,'editar')

			console.log(data)

			// return false
			
			$.ajax({
				dataType: 'json',
				async: false,
				url: '../server/public/api/backlogs/edit',
				type: 'POST',
				data: data,
				success: function (data, status, xhr) {
					console.log(data)
					location.reload();
				},
				error: function (xhr) {
					$("#mensaje").html(alertDismissJS("No se pudo completar la operaci??n: " + xhr.status + " " + xhr.statusText, 'error'));
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
				url: '../server/public/api/backlog/list',
				type: 'POST', 
				data: {id:id},
				success: function (data, status, xhr) {
					console.log(data);
					let datas = data.data[0],
						proyectos = data.proyectos;

					$("#nombre_editar").val(datas.nombre);
					$("#estado_editar").val(datas.estado);
					$("#id_backlog").val(datas.id_backlog);

					let options = '<option> - </option>';
					for(let k=0;k < proyectos.length; k++){
						let element = proyectos[k];
						let selected = '';

						if(element.id_proyecto == datas.id_proyecto){
							selected = 'selected';
						}

						console.log(element.id_proyecto,datas.id_proyecto)
						options += '<option '+selected+' value="'+element.id_proyecto+'">'+element.nombre+'</option>'
					}

					$("#id_proyecto_editar").html(options);
					$('#modalEditar').modal();
				},
				error: function (xhr) {
					$("#msjRegistro").html(alertDismissJS("No se pudo completar la operaci??n: " + xhr.status + " " + xhr.statusText, 'error'));
				}
			});

		}

		function preguntaBorrado(id){
			$('.modal-eliminar').modal();
			$("#id_eliminar").val(id);
			$("#titulo_eliminar").html("??Desea eliminar el proyecto?");
		}

		function confirmarBorrado(){
			let id_backlog = $("#id_eliminar").val();

			$.ajax({
				dataType: 'html',
				type: 'POST',
				url: '../server/public/api/backlogs/delete',
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
					$("#mensaje_eliminar").html(alertDismissJS("No se pudo completar la operaci??n: " + xhr.status + " " + xhr.statusText, 'error'));
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
