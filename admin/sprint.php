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
						<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">Iniciar</button>
					</div>
					<div class="col-md-9" id="mensaje"></div>

				</div>
                <br><br>
                <div id="kanban"></div>  
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
							<table style="width:490; margin:15px auto 0 auto">
								<tr>
									<td align="right" class="labelmodal">Proyecto:</td>
									<td align="left">
										<select class="input_medium" id="id_proyecto" onchange="listar(this);">
											<option value=""> - </option>
										</select>
									</td>
								</tr>
								<tr>
									<td align="right" class="labelmodal">US:</td>
									<td align="left">
										<select class="input_medium" id="id_user_storie" multiple="multiple">
											<option value=""> - </option>
										</select>
									</td>
								</tr>
								<tr>
									<td align="right" class="labelmodal">Fecha fin (2 semanas por defecto):</td>
									<td align="left">
										<input type="date" id="fecha_fin" name="trip-start">
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
        $(document).ready(function () {
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
                 localData: [
                          { id: "1161", state: "new", label: "Combine Orders", tags: "orders, combine", hex: "#5dc3f0", resourceId: 3 },
                          { id: "1645", state: "work", label: "Change Billing Address", tags: "billing", hex: "#f19b60", resourceId: 1 },
                          { id: "9213", state: "new", label: "One item added to the cart", tags: "cart", hex: "#5dc3f0", resourceId: 3 },
                          { id: "6546", state: "done", label: "Edit Item Price", tags: "price, edit", hex: "#5dc3f0", resourceId: 4 },
                          { id: "9034", state: "new", label: "Login 404 issue", tags: "issue, login", hex: "#6bbd49" }
                 ],
                 dataType: "array",
                 dataFields: fields
             };
            var dataAdapter = new $.jqx.dataAdapter(source);
            var resourcesAdapterFunc = function () {
                var resourcesSource =
                {
                    localData: [
                          { id: 0, name: "No name", image: "../../jqwidgets/styles/images/common.png", common: true },
                          { id: 1, name: "Andrew Fuller", image: "../../images/andrew.png" },
                          { id: 2, name: "Janet Leverling", image: "../../images/janet.png" },
                          { id: 3, name: "Steven Buchanan", image: "../../images/steven.png" },
                          { id: 4, name: "Nancy Davolio", image: "../../images/nancy.png" },
                          { id: 5, name: "Michael Buchanan", image: "../../images/Michael.png" },
                          { id: 6, name: "Margaret Buchanan", image: "../../images/margaret.png" },
                          { id: 7, name: "Robert Buchanan", image: "../../images/robert.png" },
                          { id: 8, name: "Laura Buchanan", image: "../../images/Laura.png" },
                          { id: 9, name: "Laura Buchanan", image: "../../images/Anne.png" }
                    ],
                    dataType: "array",
                    dataFields: [
                         { name: "id", type: "number" },
                         { name: "name", type: "string" },
                         { name: "image", type: "string" },
                         { name: "common", type: "boolean" }
                    ]
                };
                var resourcesDataAdapter = new $.jqx.dataAdapter(resourcesSource);
                return resourcesDataAdapter;
            }
            $('#kanban').jqxKanban({
                resources: resourcesAdapterFunc(),
                source: dataAdapter,
                columns: [
                    { text: "Backlog", dataField: "new" },
                    { text: "In Progress", dataField: "work" },
                    { text: "Done", dataField: "done" }
                ]
            }); 
        });
    </script>
  </body>
</html>