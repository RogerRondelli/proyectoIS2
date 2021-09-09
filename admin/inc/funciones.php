<?php
include("mysql.php");
$db = DataBase::conectar();
$db->setQuery("SELECT id_local,id_empresa from locales where commers = 1 ");
$com = $db->loadObject();
define('ID_COMMER', $com->id_local);
define('ID_COMMER_EMPRESA', $com->id_empresa);

require_once(__DIR__."/config.php");

function nombreEmpresa(){
	$db = DataBase::conectar();
	$db->setQuery("SELECT marca from empresas where id_empresa = ".$_SESSION['sfpy_usuario_empresa']);
	$u = $db->loadObject();
	return $u->marca;
}

function datosUsuario($id){
	$db = DataBase::conectar();
	$db->setQuery("SELECT * from usuarios where id_usuario=".$id);
	$u = $db->loadObject();
	return $u;
}

function datosRol($id){
	$db = DataBase::conectar();
	$db->setQuery("SELECT * from roles where id_rol=".$id);
	$u = $db->loadObject();
	return $u;
}

function productos(){
	$db = DataBase::conectar();
	$db->setQuery("SELECT * from productos where id_empresa = ".$_SESSION['sfpy_usuario_empresa']);
	$list = $db->loadObjectList();
	return $list ;
}

function datosTimbrado($id){
	$db = DataBase::conectar();
	$db->setQuery("SELECT * from timbrados where id_timbrado=".$id);
	// echo "SELECT * from productos where id_producto=".$id;
	$u = $db->loadObject();
	return $u;
}

function datosLocales($id){
	$db = DataBase::conectar();
	$db->setQuery("SELECT * from locales where id_local=".$id);
	$u = $db->loadObject();
	return $u;
}

function datosEmpresas($id){
	$db = DataBase::conectar();
	$db->setQuery("SELECT * from empresas where id_empresa=".$id);
	$u = $db->loadObject();
	return $u;
}

function fechaLatina($fecha){
    $fecha = substr($fecha,0,10);
    list($anio,$mes,$dia)=explode("-",$fecha);
	if (!$anio){
		return "";
	}else{
		return $dia."/".$mes."/".$anio;
	}
}

function fechaLatinaHora($fecha){
    list($anio,$mes,$dia)=explode("-",$fecha);
	$hora = substr($fecha,11,8);
	if (!$anio){
		return "";
	}else{
		return substr($dia,0,2)."/".$mes."/".$anio." ".$hora;
	}
}

function fechaMYSQL($fecha){
    $fecha = substr($fecha,0,10);
    list($dia,$mes,$anio)=explode("/",$fecha);
    return $anio."-".$mes."-".$dia;
}

function fechaMYSQLHora($fecha){
    $fecha_sola = substr($fecha,0,10);
	$fecha_hora = substr($fecha,11,16);
    list($dia,$mes,$anio)=explode("/",$fecha_sola);
	list($hora,$min) = explode(":",$fecha_hora);
    return $anio."-".$mes."-".$dia." ".$hora.":".$min;
}

function getAutoincrement($table){
	$db = DataBase::conectar();
	$db->setQuery("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = '$table'");
	$r = $db->loadObject();
	return $r;
}

function redondearGs($gs){
	if (strlen($gs) >= 4){
	   $a = (int)$gs / 100;
	   $b = round($a);
	   $c = $b * 100;
	   return $c;
	}else if (strlen($gs) <= 3)	{
		$a = (int)$gs / 100;
		$b = round($a);
	    $c = $b * 100;
		return $c;
	}
}

function fechaEspanol($x){
	$dias = array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
	$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	if ($x == "dia"){
		return $dias[date('w')];
	}else{
		return $dias[date('w')].", ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
	}
}

function menu($id,$tipo){

	$id_usuario = datosUsuario($id)->id_usuario;

	$db = DataBase::conectar();
	$db2 = DataBase::conectar();
	$db->setQuery("select us.rol, rm.id_menu, um.menu, um.submenu, um.url, um.orden, us.nombre_usuario
		from usuarios us
		inner join roles_menu rm on us.rol = rm.id_rol
		inner join menus um on um.id_menu = rm.id_menu where um.subsubmenu=0 and  um.estado = 1 and us.id_usuario = $id order by orden");

	$menus = $db->loadObjectList();

	$salida_menu = "<ul class='nav navbar-nav'>";
		//<li class='active'><a href='./index.php'>Inicio</a></li>";

	$menuActual = '';
	$usoSubmenu = 0;
	foreach($menus as $m){
		$id_menu = "menu".$m->id_menu;
		$submenu = $m->submenu;
		$menu = $m->menu;
		$url = $m->url;
		$nombre = ucfirst($m->nombre_usuario);
		if($tipo == 2){
			if ($submenu == '-'){
				if ($usoSubmenu > 0){
					$salida_menu .= "</ul></li>";
				}
				$salida_menu .= "<li><a href='$url'>$menu</a></li>";
			}else{
				if ($menu != $menuActual){
					if ($usoSubmenu > 0){
						$salida_menu .= "</ul></li>";
					}
					if ($m->url == '') {
						$salida_menu .= "<li class='dropdown'>
						  <a href='#' class='dropdown-toggle' data-toggle='dropdown'>$menu <b class='caret'></b></a>
						  <ul class='dropdown-menu'>";
	
						$salida_menu .= "<li class='dropdown dropdown-submenu'>
											<a href='#' class='dropdown-toggle' data-toggle='dropdown'>$submenu</a>
											  <ul class='dropdown-menu'>";
	
						$db2->setQuery("select us.rol, rm.id_menu, um.menu, um.submenu, um.url, um.orden, us.nombre_usuario
										from usuarios us
										inner join roles_menu rm on us.rol = rm.id_rol
										inner join menus um on um.id_menu = rm.id_menu where  um.subsubmenu=".$m->id_menu." and um.estado = 1 and us.id_usuario = $id order by orden");
						$menus2 = $db2->loadObjectList();
						foreach($menus2 as $m2){
							$salida_menu .= "<li><a href='".$m2->url."'>".$m2->submenu." </a></li>";
						}
						$salida_menu .="</ul></li>";
	
					}else{
						$salida_menu .= "<li class='dropdown'>
							<a href='#' class='dropdown-toggle' data-toggle='dropdown'>$menu<b class='caret'></b></a>
							<ul class='dropdown-menu'>";
	
						$salida_menu .= "<li><a href='$url'>$submenu</a></li>";
	
					}
	
					$db2->setQuery("select us.rol, rm.id_menu, um.menu, um.submenu, um.url, um.orden, us.nombre_usuario
									from usuarios us
									inner join roles_menu rm on us.rol = rm.id_rol
									inner join menus um on um.id_menu = rm.id_menu where um.subsubmenu = 0 AND   um.menu='".$menu."' and um.estado = 1 and us.id_usuario = $id order by orden");
					$menus2 = count($db2->loadObjectList());
					if ($menus2 == 1 ){
						$salida_menu .= "</ul></li>";
						$usoSubmenu  = 0;
					}
					$menuActual = $menu;
				}else{
					$usoSubmenu++;
					if ($m->url == '') {
						$salida_menu .= "<li class='dropdown dropdown-submenu'>
											<a href='#' class='dropdown-toggle' data-toggle='dropdown'>$submenu</a>
											  <ul class='dropdown-menu'>";
	
						$db2->setQuery("select us.rol, rm.id_menu, um.menu, um.submenu, um.url, um.orden, us.nombre_usuario
										from usuarios us
										inner join roles_menu rm on us.rol = rm.id_rol
										inner join menus um on um.id_menu = rm.id_menu where  um.subsubmenu=".$m->id_menu." and um.estado = 1 and us.id_usuario = $id order by orden");
						$menus2 = $db2->loadObjectList();
						foreach($menus2 as $m2){
							$salida_menu .= "<li><a href='".$m2->url."'>".$m2->submenu."</a></li>";
						}
						$salida_menu .="</ul></li>";
					}else{
						$salida_menu .= "<li><a href='$url'>$submenu</a></li>";
					}
	
				}
			}
		}
	}

	if($tipo == 1) {
		$salida_menu .= "</ul></ul>
						<ul class='nav navbar-nav navbar-right'>
							<li class='dropdown'>
								  <a href='#' class='dropdown-toggle' data-toggle='dropdown'>$nombre<b class='caret'></b></a>
								  <ul class='dropdown-menu'>
									<li><a href='./logout.php'>Salir</a></li>
								  </ul>
							</li>
						</ul>
					  ";
	}
          		
	echo $salida_menu;
}

function nombrePagina($pagina){
	$db2 = DataBase::conectar();
	$db2->setQuery("SELECT titulo from menus where url like '%".$pagina."%'");
	$pa = $db2->loadObject();
	return $pa->titulo;
}

function verificaLogin(){
	session_start();
	if(!isset($_SESSION['sfpy_usuario'])){
		header('Location:index.php');
	}
}

function dismissAlertPHP($mensaje, $tipo){

	/* Tipos:
	alert-success
	alert-info
	alert alert-warning
	alert alert-danger*/

	$dismiss = '<div class="alert '.$tipo.' alert-dismissable">
	  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.$mensaje.'</div>';
	return $dismiss;
}

function alertDismiss($msj, $tipo){
	switch ($tipo){
		case 'error':
			$salida = "<div class='alert alert-danger alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<span class='glyphicon glyphicon-exclamation-sign'>&nbsp;</span>$msj</div>";
		break;

		case 'ok':
			$salida = "<div class='alert alert-success alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<span class='glyphicon glyphicon-ok'>&nbsp;</span>$msj</div>";
		break;

		case 'yellow':
			$salida = "<div class='alert alert-warning alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<span class='glyphicon glyphicon-ok'>&nbsp;</span>$msj</div>";
		break;

	}
	return $salida;
}

function piePagina(){
	//include ("funciones.php");
	$db = DataBase::conectar();

	include ("structure/name.php");
	$pie = "";

	$option_select = '';

	if ($_SESSION['rol'] == 1 || $_SESSION['sfpy_usuario_acceso'] == 0){
		$a="SELECT * FROM empresas ";
	}else{
		$a="SELECT * FROM empresas where id_empresa = ".$_SESSION['sfpy_usuario_empresa'];
	}
	$empresas=mysqli_query($db,$a);
	//modulo de acceso al contenido
		$validar_rol=' where 1=1 ';
		$alias = '';
		$empresa = 1;
		$columna ='id_local';
		// include ('acceso.php');
	// fin modulo acceso al contenido
	$a="SELECT * FROM locales $validar_rol";
	$a2="SELECT * FROM locales $validar_rol and id_local_padre = 0";
	$r=mysqli_query($db,$a);
	$r2=mysqli_query($db,$a2);

	$pie .= '<div class="modal fade" id="changeLocal" role="dialog">
							<div class="modal-dialog ">
								<div class="modal-content" >
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title">Cambiar Local</h4>
									</div>
									<div class="modal-body">
										<h5 class="modal-title" id="bkp_result">Elija el Local</h5>
										<select id="change_select_local" class="form-control">
											'.$option_select.'
										</select>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
										<button type="button" class="btn btn-success" onclick="aceptar_cambio()">Aceptar</button>
									</div>
								</div>
							</div>
						</div>';

	$pie .= '<div class="modal fade" id="bkp" role="dialog">
						<div class="modal-dialog modal-sm">
							<div class="modal-content modal-pago" >
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="modal-title">Backup</h4>
								</div>
								<div class="modal-body">
									<h5 class="modal-title" id="men_proceso">La copia de seguridad esta en proceso...</h5>
									<div id="men_respuesta"></div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
								</div>
							</div>
						</div>
					</div>';
 	return $pie;
}

function exportarExcel($datos, $titulo){

	$hoy=date('d-m-Y');
	$nombre='xls/Exportado_'.$titulo.'_'.$hoy.".xls";


	$xml = simplexml_load_string($datos);
	$salida = "<table border='1'>";
	foreach ($xml->Worksheet->Table->Row as $row) {
	   $celda = $row->Cell;
	   $salida .= "<tr>".$celda;
	   foreach ($celda as $cell) {
			$salida .= "<td>".$cell->Data."</td>";
		}
		$salida .= "</tr>";
	}
	$salida .= "</table>";

	file_put_contents($nombre, utf8_decode($salida));

	echo $nombre;
}

function mostrar_mensaje($mensaje,$css=""){
	if (empty($css)){
		$css="danger";
	}
	echo '
	<div class="alert alert-'.$css.'" id="alerta" style="text-align:center;">
	'.$mensaje.'
	</div>
	';
}
?>
