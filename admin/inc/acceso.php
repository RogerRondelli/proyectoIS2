<?php 
	if (session_id() == '') session_start();
	$validar_rol=' where 1=1 ';
	if ($empresa == 0) {// por empresa 
		if ($_SESSION['rol'] != 1) {
			if($_SESSION['sfpy_usuario_acceso'] == 0){
				$validar_rol=' where 1=1 ';
			}else{
				$validar_rol =  ' where '.$alias.'id_empresa='.$_SESSION['sfpy_usuario_empresa'].' ';
			}
		}
	}elseif($empresa == 1){// locales
		if ($_SESSION['rol'] != 1) {	
			if ($_SESSION['sfpy_usuario_acceso'] == 1) {
				// $database = DataBase::conectar();
				// $queryDB= "select * from locales where id_empresa = ".$_SESSION['sfpy_usuario_empresa'];
				// $r=mysqli_query($database,$queryDB);
				// $ban = 0 ; 
				// while($row_tran=mysqli_fetch_array($r)){
				// 	if ($ban == 0) {
				// 		$validar_rol =' where ( '.$alias.$columna.'='.$row_tran['id_local'];
				// 		$ban = 1;
				// 	}else{
				// 		$validar_rol .=' or '.$alias.$columna.'='.$row_tran['id_local'];
				// 	}
				// }
				// $validar_rol .=' )';
			}else if ($_SESSION['sfpy_usuario_acceso'] == 2){
				if ( $_SESSION['modo'] == 1 && isset($interesados) ) {
					$database = DataBase::conectar();
					$queryDB= "select * from locales where id_empresa = ".$_SESSION['sfpy_usuario_empresa'];
					$r=mysqli_query($database,$queryDB);
					$ban = 0 ; 
					while($row_tran=mysqli_fetch_array($r)){
						if ($ban == 0) {
							$validar_rol =' where ( '.$alias.$columna.'='.$row_tran['id_local'];
							$ban = 1;
						}else{
							$validar_rol .=' or '.$alias.$columna.'='.$row_tran['id_local'];
						}
					}
					$validar_rol .=' )';
				}else{
					$database = DataBase::conectar();
					$queryDB= "select * from locales";
					$r = mysqli_query($database,$queryDB);
			 	}
			}else if ($_SESSION['sfpy_usuario_acceso'] == 3){
				if ( $_SESSION['modo'] == 1 && isset($interesados) ) {
					$database = DataBase::conectar();
					$queryDB= "select * from locales where id_empresa = ".$_SESSION['sfpy_usuario_empresa'];
					$r=mysqli_query($database,$queryDB);
					$ban = 0 ; 
					while($row_tran=mysqli_fetch_array($r)){
						if ($ban == 0) {
							$validar_rol =' where ( '.$alias.$columna.'='.$row_tran['id_local'];
							$ban = 1;
						}else{
							$validar_rol .=' or '.$alias.$columna.'='.$row_tran['id_local'];
						}
					}
					$validar_rol .=' )';
				}else{

					$validar_rol =  ' where '.$alias.$columna.'='.$_SESSION['sfpy_usuario_local'].' ';
			 	}
			}else if ($_SESSION['sfpy_usuario_acceso'] == 0){
				$validar_rol=' where 1=1 ';
			}	
		}
	}

?>