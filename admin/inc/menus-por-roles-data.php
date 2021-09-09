<?php
	include ("funciones.php");
	verificaLogin();

	$q = $_REQUEST['q'];
	$id_rol= $_REQUEST['id_rol'];

	$id_empresa = $_SESSION['id_empresa'];

	switch ($q){

	case 'ver_menus':
		$query = "SELECT id_menu, concat_ws(' - ',menu, submenu) as menu_submenu,subsubmenu FROM menus order by orden asc";
		$db = DataBase::conectar();
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		foreach($rows as $r){
			$menu = $r->menu_submenu;
			if($r->subsubmenu != 0){
				$menu = "&nbsp&nbsp&nbsp".$r->menu_submenu;
			}
			$datos[] = array(
				'id_menu' => $r->id_menu,
				'menu_submenu' => $menu,
				'subsubmenu' => $r->subsubmenu
			  );
		}

		echo json_encode($datos);
	break;

	case 'filtro_menu':
		$db = DataBase::conectar();
		$id_rol= $_REQUEST['id'];

		$a="SELECT * FROM roles_menu where id_rol=$id_rol";

		$db->setQuery($a);
		$rows1 = $db->loadObjectList();

		$where ='';
		$i=0;
		foreach($rows1 as $r1){
			$i++;
			if ($i==1) {
				$where .=' where id_menu!='.$r1->id_menu;
			}else{
				$where .=' and id_menu!='.$r1->id_menu;
			}
		}

		$query = "SELECT id_menu, CONCAT_WS(' - ',menu, submenu) AS menu_submenu,subsubmenu FROM menus $where
					ORDER BY 2 ASC";
					// echo "SELECT id_menu, CONCAT_WS(' - ',menu, submenu) AS menu_submenu FROM menus $where
					// ORDER BY 2 ASC";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		foreach($rows as $r){
			$menu = $r->menu_submenu;
			if($r->subsubmenu != 0){
				$menu = "&nbsp&nbsp&nbsp".$r->menu_submenu;
			}
			$datos[] = array(
				'id_menu' => $r->id_menu,
				'menu_submenu' => $menu,
				'subsubmenu' => $r->subsubmenu
			  );
		}

		echo json_encode($datos);
	break;

	case 'ver_roles':
		$query = "SELECT id_rol, rol FROM roles where estado = 1  order by 2 asc";

		$db = DataBase::conectar();
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		foreach($rows as $r){
			
			$datos[] = array(
				'id_rol' => $r->id_rol,
				'rol' => $r->rol
			  );
		}

		echo json_encode($datos);
	break;

		case 'ver_menus_user':
		$query="SELECT *, concat_ws(' - ',menu, submenu) as menu_submenu,subsubmenu  FROM roles_menu, menus where id_rol='$id_rol' and roles_menu.id_menu=menus.id_menu  order by orden asc";

		$db = DataBase::conectar();
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		foreach($rows as $r){
			$menu = $r->menu_submenu;
			if($r->subsubmenu != 0){
				$menu = "&nbsp&nbsp&nbsp".$r->menu_submenu;
			}
			$datos[] = array(
				'id_menu_select' => $r->id_menu,
				'menu_user_select' => $menu,
				'subsubmenu_select' => $r->subsubmenu
			  );
		}
		echo json_encode($datos);
		// echo json_encode(array('datos'=>$query));
	break;

	case 'insert':
			$row=explode(',',$_REQUEST['row']);
			$id_rol=$_REQUEST['id'];
			$db = DataBase::conectar();
			$tipoAlert =0;
			foreach ($row as $key => $value) {
				$q="insert into roles_menu (id_rol,id_menu) values ('$id_rol', '$value');";
				$db->setQuery($q);
				if($db->alter()){

				}else{
					$tipoAlert = 1;
				}
			}
			echo $tipoAlert;
			// echo $q;

	break;
	case 'remover':
			$row=explode(',',$_REQUEST['row']);
			$id_rol=$_REQUEST['id'];
			$db = DataBase::conectar();
			$tipoAlert =0;
			foreach ($row as $key => $value) {
				$q="delete from roles_menu where id_rol='$id_rol' and id_menu='$value';";
				$db->setQuery($q);
				if($db->alter()){

				}else{
					$tipoAlert = 1;
				}
			}
			echo $tipoAlert;
			// echo $q;

	break;
}

?>
