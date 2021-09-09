<?php
	session_start();
	session_destroy();
	setcookie("sfpy_recordar_usuario","deleted", time() - 3600, "/");
	header('Location:./index.php');
?>