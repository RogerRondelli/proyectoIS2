<?php
	include_once("inc/funciones.php");
	$id_usuario = $_POST['id_usuario'];
	$nombre_usuario = $_POST['nombre_usuario'];
	$rol = $_POST['rol'];

	$acceso = datosRol($rol)->acceso;
	session_start();
	$_SESSION['sfpy_usuario'] = $id_usuario;
	$_SESSION['sfpy_usuario_nombre'] = $nombre_usuario;
	$_SESSION['sfpy_usuario_acceso'] = $acceso;
	$_SESSION['rol'] = $rol;
	setcookie("sfpy_recordar_usuario",$id_usuario, time()+3600*24*180, "/");
?>
