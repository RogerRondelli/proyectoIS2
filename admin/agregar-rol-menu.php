<!-- Bootstrap core CSS -->
<link href="css/bootstrap.css" rel="stylesheet">
<?php
include('inc/funciones.php');
$menus=$_POST['listbox'];
$rol=$_POST['jqxcombobox'];

if (empty($menus)){
mostrar_mensaje("Seleccione los menus",'danger');	
}
elseif (empty($rol)){
mostrar_mensaje("Seleccione un rol",'danger');	
}
else{
$ex=explode(',',$menus);
$cant=count($ex);

$i=0;
while ($i<$cant){
$id_menu=$ex[$i];	


$verifica_menu=rowMaestro('roles_menu','id_rol',$rol,'id_menu',$id_menu);
$id_rol_menu=$verifica_menu['id_rol_menu'];

$row_menu=RowMaestro('roles','id_rol',$rol);
$rol_des=$row_menu['rol'];

$row_menu=RowMaestro('menus','id_menu',$id_menu);
$menu=$row_menu['submenu'];

//verifica que no exista ese usuario con el mismo menu si no existe inserta

if ($verifica_menu){
	mostrar_mensaje("<b>$rol_des</b> ya cuentan con <b>$menu</b>",'warning');		
}else{
	$q="insert into roles_menu(id_rol,id_menu) values('$rol','$id_menu')";
	$db = DataBase::conectar();
	mysqli_query($db,$q);
	$id=mysqli_insert_id($db);
	
	if ($id>0){
	mostrar_mensaje("Se ha agregado <b>$menu</b> a los <b>$rol_des</b>",'success');	
	}
	else{
	mostrar_mensaje("<b>ERROR</b> No agregado <b>$menu</b> a los <b>$rol_des</b>",'danger');		
	}	
}
$i++;
}
}

?>