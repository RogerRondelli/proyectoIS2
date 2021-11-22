<?php
	include ("inc/funciones.php");
	verificaLogin();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include_once "structure/head.php"; ?>
    <link rel="stylesheet" href="https://www.jqwidgets.com/jquery-widgets-documentation/jqwidgets/styles/jqx.base.css" type="text/css" />
    <!-- <script type="text/javascript" src="https://www.jqwidgets.com/jquery-widgets-documentation/scripts/jquery-1.11.1.min.js"></script> -->
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
	<script>
		var id = <?=$_GET['id']?>;
		var usuario = <?=$_SESSION['sfpy_usuario']?>;
	</script>
  </head>

  <body>
    <!-- Fixed navbar -->
    <?php include_once "structure/navbar.php" ?>

	<!-- Wrap all page content here -->
    <div id="wrap">
		<div class="container">
			<div class="page-header">
				<h2>Tablero</h2>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="col-md-3"  style="padding-left:0">
						<button type="button" onclick="statusSprint()" style="display:none" class="iniciar btn btn-success btn-sm">Iniciar</button>
						<button type="button" onclick="statusSprint()" style="display:none" class="cerrar btn btn-danger btn-sm">Cerrar</button>
					</div>
					<div class="col-md-9" id="mensaje"></div>
				</div>
                <br><br>
                <div id="kanban" style="display:none"></div>  
				<!-- The Modal -->
				<div class="modal" id="myModal">
					<div class="modal-dialog">
						<div class="modal-content">

						<!-- Modal Header -->
						<div class="modal-header">
							<h4 class="modal-title">Agregar Sprint</h4>
						</div>

						<!-- Modal body --> 
						<div class="modal-body">
							<form id="formComentario">
								<input type="hidden" name="id_usuario" value="<?=$_SESSION['sfpy_usuario']?>">
								<input type="hidden" name="id_sprint" id="id_sprint">
								<textarea name="comentario" id="comentario" cols="30" rows="10" style="width: 100%;height: 150px;"></textarea>
							</form>
						</div>

						<!-- Modal footer -->
						<div class="modal-footer">
							<button type="button" class="btn btn-success btn-sm" onclick="guardar()">Guardar</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
						</div>

						</div>
					</div>
				</div>
			</div>
		</div> <!-- /container -->
	</div> <!-- /wrap -->

	<?php echo piePagina(); ?>

	<script type="text/javascript">

        $(document).ready(function () {
        });
	</script>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/bootstrap.min.js"></script>
	<!-- <script src="js/menuHover.js"></script> -->
	<script src="js/funciones.js?v<?=$version_js?>"></script>
    <script type="text/javascript" src="https://www.jqwidgets.com/jquery-widgets-documentation/jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="https://www.jqwidgets.com/jquery-widgets-documentation/jqwidgets/jqxsortable.js"></script>
    <script type="text/javascript" src="https://www.jqwidgets.com/jquery-widgets-documentation/jqwidgets/jqxkanban.js"></script>
    <script type="text/javascript" src="https://www.jqwidgets.com/jquery-widgets-documentation/jqwidgets/jqxdata.js"></script>
    <script type="text/javascript">
		var estados = ['new','work','done'];
		var estado_sprint;
		console.log(estados,estados[0],estados.indexOf('new'))
        $(document).ready(function () {
			// table(id);	
			us();
			$('#id_sprint').val(id);
			$('#kanban').on('itemMoved', function (e) {
				var args = e.args;
				// e.preventDefault()
				// return false
				var datas = {
					id : args.itemId,
					id_usuario : args.itemData.resourceId,
					estado : estados.indexOf(args.newColumn.dataField)
				}

				console.log('data',datas,args,usuario)
				if(datas.id_usuario != usuario && usuario != 1) return false
				if(estado_sprint == "Cerrar") return false
				// return false
				$.ajax({
					dataType: 'json',
					async: false,
					url: '../server/public/api/sprints/updateStatusKanban',
					type: 'POST', 
					data: datas,
					success: function (data, status, xhr) {
						console.log(data);
						console.log('asdasdasdasd',e,estados.indexOf(args.newColumn.dataField))
						$('#myModal').modal('show')
						// table(data.data);
						// location.reload();
					},
					error: function (xhr) {
						$("#msjRegistro").html(alertDismissJS("No se pudo completar la operación: " + xhr.status + " " + xhr.statusText, 'error'));
					}
				});
			});
			console.log('column',countWork,countNew,countDone)
		});

		let localData2= [
						  { id: "1161", state: "new", label: "Combine Orders", tags: "orders, combine", hex: "#5dc3f0", resourceId: 3 },
						  { id: "1645", state: "work", label: "Change Billing Address", tags: "billing", hex: "#f19b60", resourceId: 1 },
						  { id: "9213", state: "new", label: "One item added to the cart", tags: "cart", hex: "#5dc3f0", resourceId: 3 },
						  { id: "6546", state: "done", label: "Edit Item Price", tags: "price, edit", hex: "#5dc3f0", resourceId: 4 },
						  { id: "9034", state: "new", label: "Login 404 issue", tags: "issue, login", hex: "#6bbd49" }
		];

		var estado,total_registros;
		function us(){
			let datas = {
				id:id
			}

			$.ajax({
				dataType: 'json',
				async: false,
				url: '../server/public/api/sprints/listTableKanban',
				type: 'POST', 
				data: datas,
				success: function (data, status, xhr) {
					estado = data.estado;
					estado_sprint = estado;
					let show = 'iniciar';
					if(estado == 'Iniciar') show = 'cerrar';
					$('.'+show).show();
					if(estado == 'Cerrar' || estado == 'Iniciar') $('#kanban').show();
					if(estado == 'Cerrar') $('.cerrar,.iniciar').hide();
					total_registros = data.data.length;
					console.log(data,estado,estado.toLowerCase(),total_registros);
					table(data.data);
					// location.reload();
				},
				error: function (xhr) {
					$("#msjRegistro").html(alertDismissJS("No se pudo completar la operación: " + xhr.status + " " + xhr.statusText, 'error'));
				}
			});
		}
		
		function table(datas){
			let localData = [];
			for(var i=0;datas.length > i;i++){
				const element = datas[i];

				localData.push({ 
					id: element.id_user_storie, 
					state: estados[element.estado], 
					label: element.descripcion, 
					tags: element.titulo, 
					hex: "#5dc3f0", 
					resourceId: element.id_usuario
				})
			}
			console.log('id',localData)

			var fields = [
					 { name: "id", type: "string" },
					 { name: "status", map: "state", type: "string" },
					 { name: "text", map: "label", type: "string" },
					 { name: "tags", type: "string" },
					 { name: "color", map: "hex", type: "string" },
					 { name: "resourceId", type: "number" }
			];

			var source =
			{
				 localData: localData,
				 dataType: "array",
				 dataFields: fields
			};
			var dataAdapter = new $.jqx.dataAdapter(source);
			
			$('#kanban').jqxKanban({
				source: dataAdapter,
				columns: [
					{ text: "To-do", dataField: "new" },
					{ text: "Doing", dataField: "work" },
					{ text: "Done", dataField: "done" }
				],
				width: '100%'
			}); 
		}

		function guardar(){
			let datas = $('#formComentario').serializeArray()
			console.log(datas)
			// return false
			$.ajax({
				dataType: 'html',
				async: false,
				url: '../server/public/api/sprints/comment',
				type: 'POST', 
				data: datas,
				success: function (data, status, xhr) {
					console.log(data);
					$('#myModal').modal('hide');
					// location.reload();
				},
				error: function (xhr) {
					$("#msjRegistro").html(alertDismissJS("No se pudo completar la operación: " + xhr.status + " " + xhr.statusText, 'error'));
				}
			});
		}
		
		function statusSprint(){
			// console.log('asdadsadasdasd')
			let estado_;
			if(estado == 'Pendiente') estado_ = 'Iniciar';
			if(estado == 'Iniciar') estado_ = 'Cerrar';
			if(estado == 'Cerrar') return false;
			
			let datas = {
				id: id,
				estado: estado_
			}

			var countDone = ($('#kanban').jqxKanban('getColumnItems', 'done')).length; 
			console.log('asdadsadasdasd',datas,total_registros,countDone)

			if(estado_ == 'Cerrar' && total_registros != countDone){
				alert('Para poder finalizar el sprint todos los UH deben de estar en la columna Done') 
				return false
			} 
			if(total_registros == 0){
				alert('El sprint no posee UH por lo que no puede ser iniciado') 
				return false
			} 
			// return false
			$.ajax({
				dataType: 'json',
				async: false,
				url: '../server/public/api/sprints/statusSprint',
				type: 'POST', 
				data: datas,
				success: function (data, status, xhr) {
					// estado = data.estado;
					console.log(data);
					// table(data.data);
					location.reload();
				},
				error: function (xhr) {
					$("#msjRegistro").html(alertDismissJS("No se pudo completar la operación: " + xhr.status + " " + xhr.statusText, 'error'));
				}
			});
		}
    </script>
  </body>
</html>