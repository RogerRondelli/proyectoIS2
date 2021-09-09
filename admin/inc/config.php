<?php
	//------------------------------------------------
	$region = 2; //1:Paraguay - 2:Argentina - 3:Bolivia
	//------------------------------------------------
	$version_js = '1'; //cambiar la version en caso de que se hubiera echo algun cambio en algun js
	//------------------------------------------------
	switch ($region) {
		case 1:
			define('REGION', 'Paraguay');
			define('MONEDA', ' Gs. ');
			define('TIPO', 1);
			define('NOM_MONEDA', 'Guaranies');
		break;
		case 2:
			define('REGION', 'Argentina');
			define('MONEDA', ' $ ');
			define('TIPO', 2);
			define('NOM_MONEDA', 'Pesos');
		break;
	}
?>