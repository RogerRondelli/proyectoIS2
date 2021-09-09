<?php
	include ("funciones.php");
	//verificaLogin();
	$q = $_REQUEST['q'];
	$id = $_REQUEST['id'];
	$id_empresa = $_SESSION['sfpy_usuario_empresa'];
	
	switch ($q){
		
		case 'ciudades':
			$db = DataBase::conectar();
			$db->setQuery("SELECT id_ciudad, ciudad from ciudades order by ciudad");
			$rows = $db->loadObjectList();
			echo json_encode($rows);
		break;

		case 'usuarios':
			//modulo de acceso al contenido
				$alias = '';
				$empresa = 1;
				$columna ='id_local';
				include ('acceso.php');
			// fin modulo acceso al contenido
			$db = DataBase::conectar();
			$db->setQuery("SELECT id_usuario, nombre_usuario from usuarios $validar_rol order by 2");
			$rows = $db->loadObjectList();
			echo json_encode($rows);
		break;
		
		case 'locales':
			//modulo de acceso al contenido
				$alias = '';
				$empresa = 0;
				$columna ='id_empresa';
				include ('acceso.php');
			// fin modulo acceso al contenido
			$db = DataBase::conectar();
			$db->setQuery("SELECT id_local, local, direccion from locales $validar_rol and estado = 1 order by local");
			$rows = $db->loadObjectList();
			echo json_encode($rows);
		
		break;
		case 'local':
			$db = DataBase::conectar();
			$id = $_REQUEST['id'];
			$db->setQuery("SELECT id_local, local, direccion from locales where id_local_padre = 0 and id_empresa = $id  and estado = 1 order by local");
			$rows = $db->loadObjectList();
			echo json_encode($rows);
		
		break;

		case 'empresas':
			$db = DataBase::conectar();
			$db->setQuery("SELECT id_empresa,marca from empresas where estado = '1' order by marca");
			$rows = $db->loadObjectList();
			echo json_encode($rows);
		break;
		
		case 'estado_productos':
			$db = DataBase::conectar();
			$db->setQuery("SELECT id_estado_producto, estado from estado_producto order by 1");		
			$rows = $db->loadObjectList();
			echo json_encode($rows);
		
		break;

		case 'roles':
			$db = DataBase::conectar();
			$db->setQuery("SELECT id_rol, rol from roles where estado = 1 order by 2");
			$rows = $db->loadObjectList();
			echo json_encode($rows);
		
		break;
		
		case 'categorias':
			//modulo de acceso al contenido
				$alias = 'c.';
				$empresa = 3;
				$columna ='';
				include ('acceso.php');
			// fin modulo acceso al contenido

			$db = DataBase::conectar();
			//$db->setQuery("SELECT id_categoria, categoria, estado from categorias order by categoria");
			$db->setQuery("SELECT c.id_categoria AS id, c.categoria AS categoria, cp.id_categoria AS parentid, cp.categoria AS categoria_padre
						,tc.id_tipo_categoria
						FROM categorias c 
						JOIN tipos_categorias tc ON tc.id_tipo_categoria = c.id_tipo_categoria
						LEFT JOIN categorias cp ON c.id_categoria_padre = cp.id_categoria $validar_rol and c.estado = 1 ORDER BY c.categoria");
			
			$rows = $db->loadObjectList();
			echo json_encode($rows);
		break;

		case 'categoriastotal':
			$db = DataBase::conectar();
			//$db->setQuery("SELECT id_categoria, categoria, estado from categorias order by categoria");
			$db->setQuery("SELECT c.id_categoria AS id, c.categoria AS categoria, cp.id_categoria AS parentid, cp.categoria AS categoria_padre
						   FROM categorias c LEFT JOIN categorias cp ON c.id_categoria_padre = cp.id_categoria  ORDER BY c.categoria");
			$rows = $db->loadObjectList();
			echo json_encode($rows);
		break;

		case 'descripcion':
			$db = DataBase::conectar();
			//$db->setQuery("SELECT id_categoria, categoria, estado from categorias order by categoria");
			$db->setQuery("SELECT c.descripcion, c.estado, c.id_categoria_padre,c.categoria  FROM categorias c LEFT JOIN categorias cp ON c.id_categoria_padre = cp.id_categoria WHERE c.id_categoria = $id ");
			$rows = $db->loadObjectList();
			echo json_encode($rows);
		break;

		case 'proveedores':
			//modulo de acceso al contenido
				$alias = '';
				$empresa = 1;
				$columna ='id_local';
				include ('acceso.php');
			// fin modulo acceso al contenido
			$db = DataBase::conectar();
			$db->setQuery("SELECT id_proveedor, nombre_fantasia from proveedores $validar_rol order by 2");
			$rows = $db->loadObjectList();
			echo json_encode($rows);
		break;
		
		case 'timbrados':
			//modulo de acceso al contenido
				$alias = '';
				$empresa = 0;
				$columna ='id_empresa';
				include ('acceso.php');
			// fin modulo acceso al contenido
			$db = DataBase::conectar();
			$db->setQuery("SELECT id_timbrado, timbrado from timbrados $validar_rol order by timbrado");
			$rows = $db->loadObjectList();
			echo json_encode($rows);
		break;

		case 'etiquetas':
			$db = DataBase::conectar();
			$db->setQuery("SELECT id_etiqueta, etiqueta from etiquetas order by etiqueta");
			$rows = $db->loadObjectList();
			echo json_encode($rows);
		break;
		
		case 'etiqueta_agregar':
			$db = DataBase::conectar();
			$etiqueta = $db->clearText($_REQUEST['etiqueta']);
			$db->setQuery("INSERT INTO etiquetas (etiqueta) values ('$etiqueta')");
			if($db->alter()){
				echo $etiqueta;
			}else{
				echo $db->getError();
			}
		break;
	}

?>