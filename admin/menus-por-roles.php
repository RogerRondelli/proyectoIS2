<?php
	include ("inc/funciones.php");
	//$pag = basename($_SERVER['PHP_SELF']);
	verificaLogin();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include_once "structure/head.php" ?>

	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>

	<!-- <link rel="stylesheet" href="css/jquery-ui.css" />
	<script type="text/javascript" src="js/jquery-ui.js"></script> -->

	<link rel="stylesheet" href="plugins/jqwidgets/styles/jqx.base.css" type="text/css" />
	<!-- <link rel="stylesheet" href="plugins/jqwidgets/styles/jqx.arctic.css" type="text/css" /> -->
	<link rel="stylesheet" href="plugins/jqwidgets/styles/jqx.metro.css" type="text/css" />

    <script type="text/javascript" src="plugins/jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="plugins/jqwidgets/jqxdata.js"></script>
    <script type="text/javascript" src="plugins/jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" src="plugins/jqwidgets/jqxscrollbar.js"></script>
	<script type="text/javascript" src="plugins/jqwidgets/jqxlistbox.js"></script>
	<script type="text/javascript" src="plugins/jqwidgets/jqxdropdownlist.js"></script>
	<script type="text/javascript" src="plugins/jqwidgets/jqxmenu.js"></script>
    <script type="text/javascript" src="plugins/jqwidgets/jqxgrid.js"></script>
    <script type="text/javascript" src="plugins/jqwidgets/jqxgrid.pager.js"></script>
    <script type="text/javascript" src="plugins/jqwidgets/jqxgrid.filter.js"></script>
    <script type="text/javascript" src="plugins/jqwidgets/jqxgrid.selection.js"></script>
    <script type="text/javascript" src="plugins/jqwidgets/jqxwindow.js"></script>
    <script type="text/javascript" src="plugins/jqwidgets/jqxinput.js"></script>
	<script type="text/javascript" src="plugins/jqwidgets/jqxcombobox.js"></script>
	<script type="text/javascript" src="plugins/jqwidgets/jqxdatetimeinput.js"></script>
	<script type="text/javascript" src="plugins/jqwidgets/jqxcalendar.js"></script>
	<script type="text/javascript" src="plugins/jqwidgets/jqxgrid.columnsresize.js"></script>
	<script type="text/javascript" src="plugins/jqwidgets/jqxcheckbox.js"></script>

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
    </style>

    <script type="text/javascript">
        $(document).ready(function () {
			var theme = 'metro';

			var sourceMenus =
            {
                datatype: "json",
                datafields: [
                     { name: 'id_menu', type: 'numeric'},
                     { name: 'menu_submenu', type: 'string'},
                     { name: 'subsubmenu', type: 'numeric'}
                ],
                id: 'id_menu',
                url: 'inc/menus-por-roles-data.php?q=ver_menus',
				cache: false,
            };

            var dataAdapterMenus = new $.jqx.dataAdapter(sourceMenus);

			$("#listbox").jqxListBox(
			{
				source: dataAdapterMenus,
				theme: theme,
				checkboxes: true,
				width: '100%',
				height: 300,
				selectedIndex: 0,
				displayMember: 'menu_submenu',
				valueMember: 'id_menu'
			});


			var sourceRol =
            {
                datatype: "json",
                datafields: [
                     { name: 'id_rol', type: 'numeric'},
                     { name: 'rol', type: 'string'}
                ],
                id: 'id_rol',
                url: 'inc/menus-por-roles-data.php?q=ver_roles',
				cache: false,
            };

            var dataAdapterRoles = new $.jqx.dataAdapter(sourceRol);


			$("#jqxcombobox").jqxComboBox(
			{
				width: '100%',
				height: 25,
				source: dataAdapterRoles,
				theme: theme,
				dropDownHeight: 250,
				promptText: 'Seleccione un Rol',
				selectedIndex: -1,
				displayMember: 'rol',
				valueMember: 'id_rol'
			});

			$("#jqxcombobox").bind('select', function(event)
			{
				if (event.args)
				{
					id_rol=event.args.item.value;

					var menu_user =
		            {
		                datatype: "json",
		                datafields: [
		                     { name: 'id_menu_select', type: 'numeric'},
		                     { name: 'menu_user_select', type: 'string'},
		                     { name: 'subsubmenu_select', type: 'numeric'}
		                ],
		                id: 'menu_user',
		                url: 'inc/menus-por-roles-data.php?q=ver_menus_user&id_rol='+id_rol,
						cache: false,
		            };

		            var dataMenus = new $.jqx.dataAdapter(menu_user);
					$("#rol_menu").jqxListBox(
					{
						source: dataMenus,
						theme: theme,
						checkboxes: true,
						width: '100%',
						height: 300,
						selectedIndex: 0,
						displayMember: 'menu_user_select',
						valueMember: 'id_menu_select'
					});

					var sourceMenus =
		            {
		                datatype: "json",
		                datafields: [
		                     { name: 'id_menu', type: 'numeric'},
							 { name: 'menu_submenu', type: 'string'},
							 { name: 'subsubmenu', type: 'numeric'}
		                ],
		                id: 'id_menu',
		                url: 'inc/menus-por-roles-data.php?q=filtro_menu&id='+id_rol,
						cache: false,
		            };

	           		var dataAdapterMenus = new $.jqx.dataAdapter(sourceMenus);
	           		console.log(dataAdapterMenus)

					$("#listbox").jqxListBox(
					{
						source: dataAdapterMenus,
						theme: theme,
						checkboxes: true,
						width: '100%',
						height: 300,
						selectedIndex: 0,
						displayMember: 'menu_submenu',
						valueMember: 'id_menu'
					});

				}
			});

			// $("#listbox").bind('select', function(event)
			// {
			// 	alert()
			// });

			//recarga solo la ventanita al pulsar el boton guardar
			$("#guardar").click(function (){
				var items = $("#listbox").jqxListBox('getCheckedItems');
                var checkedItems = "";
                $.each(items, function (index) {
                    if (index < items.length - 1) {
                        checkedItems += this.value + ", ";
                    }
                    else checkedItems += this.value;
                });

                $.ajax({
					url: 'inc/menus-por-roles-data.php',
					type: 'post',
					data: {q: 'insert', row:checkedItems,id:id_rol},
					success: function(respuesta){
						console.log(respuesta)
						var dataMenus = new $.jqx.dataAdapter(menu_user);
						$("#rol_menu").jqxListBox(
						{
							source: dataMenus,
							theme: theme,
							checkboxes: true,
							width: '100%',
							height: 300,
							selectedIndex: 0,
							displayMember: 'menu_user_select',
							valueMember: 'id_menu_select'
						});

						var sourceMenus =
			            {
			                datatype: "json",
			                datafields: [
			                     { name: 'id_menu', type: 'numeric'},
								 { name: 'menu_submenu', type: 'string'},
								 { name: 'subsubmenu', type: 'numeric'}
			                ],
			                id: 'id_menu',
			                url: 'inc/menus-por-roles-data.php?q=filtro_menu&id='+id_rol,
							cache: false,
			            };

		           		var dataAdapterMenus = new $.jqx.dataAdapter(sourceMenus);

						$("#listbox").jqxListBox(
						{
							source: dataAdapterMenus,
							theme: theme,
							checkboxes: true,
							width: '100%',
							height: 300,
							selectedIndex: 0,
							displayMember: 'menu_submenu',
							valueMember: 'id_menu'
						});
					},
					error: function(xhr,err){
					console.log("readyState1: "+xhr.readyState+"\nstatus: "+xhr.status+"\n \n responseText: "+xhr.responseText);
					}
				});

				var menu_user =
	            {
	                datatype: "json",
	                datafields: [
	                    { name: 'id_menu_select', type: 'numeric'},
	                    { name: 'menu_user_select', type: 'string'}
	                ],
	                id: 'menu_user',
	                url: 'inc/menus-por-roles-data.php?q=ver_menus_user&id_rol='+id_rol,
					cache: false,
	            };
			});

			//recarga solo la ventanita al pulsar el boton guardar
			$("#remover").click(function (){
                var items = $("#rol_menu").jqxListBox('getCheckedItems');
                var checkedItems = "";
                $.each(items, function (index) {
                    if (index < items.length - 1) {
                        checkedItems += this.value + ", ";
                    }
                    else checkedItems += this.value;
                });
                $.ajax({
					url: 'inc/menus-por-roles-data.php',
					type: 'post',
					data: {q: 'remover', row:checkedItems,id:id_rol},
					success: function(respuesta){
						// console.log(respuesta)
						var dataMenus = new $.jqx.dataAdapter(menu_user);
						$("#rol_menu").jqxListBox(
						{
							source: dataMenus,
							theme: theme,
							checkboxes: true,
							width: '100%',
							height: 300,
							selectedIndex: 0,
							displayMember: 'menu_user_select',
							valueMember: 'id_menu_select'
						});
						var sourceMenus =
			            {
			                datatype: "json",
			                datafields: [
			                     { name: 'id_menu', type: 'numeric'},
								 { name: 'menu_submenu', type: 'string'},
								 { name: 'subsubmenu', type: 'numeric'}
			                ],
			                id: 'id_menu',
			                url: 'inc/menus-por-roles-data.php?q=filtro_menu&id='+id_rol,
							cache: false,
			            };

		           		var dataAdapterMenus = new $.jqx.dataAdapter(sourceMenus);

						$("#listbox").jqxListBox(
						{
							source: dataAdapterMenus,
							theme: theme,
							checkboxes: true,
							width: '100%',
							height: 300,
							selectedIndex: 0,
							displayMember: 'menu_submenu',
							valueMember: 'id_menu'
						});
					},
					error: function(xhr,err){
					console.log("readyState1: "+xhr.readyState+"\nstatus: "+xhr.status+"\n \n responseText: "+xhr.responseText);
					}
				});

				var menu_user =
	            {
	                datatype: "json",
	                datafields: [
	                    { name: 'id_menu_select', type: 'numeric'},
	                    { name: 'menu_user_select', type: 'string'}
	                ],
	                id: 'menu_user',
	                url: 'inc/menus-por-roles-data.php?q=ver_menus_user&id_rol='+id_rol,
					cache: false,
				};
				
				
			});
			// $("#cancelar").jqxButton({ theme: theme});
			// $("#guardar").jqxButton({ theme: theme});
			// $("#remover").jqxButton({ theme: theme});

			$conexion = true;
			$("#listbox").bind('checkChange', function (event) {
				var item = event.args.item;
				var value = event.args.item.originalItem.subsubmenu
				var id = event.args.item.value
				// console.log(event.args.item.originalItem)
				if(value != 0){
					$conexion = true;
				}
				console.log(value)
				if(item.checked && value != 0 && $conexion ){
					ids = $("#listbox").jqxListBox('getItem', value);
					if(!ids.checked){
						$("#listbox").jqxListBox('checkIndex', value);
					}
					$conexion = false;
				}else{

					if($conexion){
						return false;
					}

					var items = $("#listbox").jqxListBox('getItems');
					var checkedItems = "";
					// console.log(items)
                    $.each(items, function (index,obj) {
						// console.log(index,b)
						if(item.checked){
							if(obj.originalItem.subsubmenu == id && !obj.checked){
								var ids = $("#listbox").jqxListBox('getItem', obj.value);
								if(!ids.checked){
									$("#listbox").jqxListBox('checkIndex', obj.value);
								}
							}
						}else{
							if(obj.originalItem.subsubmenu == id && obj.checked){
								var ids = $("#listbox").jqxListBox('getItem', obj.value);
								if(ids.checked){
									$("#listbox").jqxListBox('uncheckIndex', obj.value);
								}
							}
						}
					});
				}
			});


			$conexion2 = true;
			$("#rol_menu").bind('checkChange', function (event) {
				var item = event.args.item;
				var value = event.args.item.originalItem.subsubmenu_select
				var id = event.args.item.value
				console.log(event.args.item.originalItem)
				if(value != 0){
					$conexion2 = true;
				}
				// console.log(value)
				if(item.checked && value != 0 && $conexion2 ){
					ids = $("#rol_menu").jqxListBox('getItem', value);
					if(!ids.checked){
						$("#rol_menu").jqxListBox('checkIndex', value);
					}
					$conexion2 = false;
				}else{

					if($conexion2){
						return false;
					}

					var items = $("#rol_menu").jqxListBox('getItems');
					var checkedItems = "";
					// console.log(items)
                    $.each(items, function (index,obj) {
						// console.log(index,b)
						if(item.checked){
							if(obj.originalItem.subsubmenu_select == id && !obj.checked){
								var ids = $("#rol_menu").jqxListBox('getItem', obj.value);
								if(!ids.checked){
									$("#rol_menu").jqxListBox('checkIndex', obj.value);
								}
							}
						}else{
							if(obj.originalItem.subsubmenu_select == id && obj.checked){
								var ids = $("#rol_menu").jqxListBox('getItem', obj.value);
								if(ids.checked){
									$("#rol_menu").jqxListBox('uncheckIndex', obj.value);
								}
							}
						}
					});
				}
			});

        });
    </script>

  </head>

  <body>
    <!-- Fixed navbar -->
	<?php include_once "structure/navbar.php" ?>

	<!-- Wrap all page content here -->
    <div id="wrap">
		<div class="container">
			<div class="page-header">
				<h2><?php echo nombrePagina(basename($_SERVER['PHP_SELF'])); ?></h2>
				<iframe id="resultado" name="resultado" class="resultado" frameborder="0" height="60" style="display: none;"></iframe>
			</div>
			<!--roles-->
			<form class="form" id="form" target="resultado"  method="post" action="agregar-rol-menu.php">
				<div class="row">
					<div class="col-md-6">
						<div style="margin-top: 7px; margin-bottom: 5px;" id="jqxcombobox"></div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div id="rol_menu">
							<?php
							mostrar_mensaje("Seleccione un rol",'warning');
							?>
						</div>
						<br>
						<input style="margin-bottom: 15px" type="button" id="remover" value="Remover Menu" class="btn btn-default btn-sm">
					</div>
					<!-- lista de menu-->
					<div class="col-md-6">
						<div id="listbox"></div>
						<br>
						<input style="margin-bottom: 15px" type="submit" id="guardar" value="Agregar Menu" class="btn btn-default btn-sm">
					</div>
				</div>
			</form>
		</div> <!-- /container -->
	</div> <!-- /wrap -->

	<?php echo piePagina(); ?>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/bootstrap.min.js"></script>
	<!-- <script src="js/menuHover.js"></script> -->
	<script src="js/funciones.js?v<?=$version_js?>"></script>
  </body>
</html>
