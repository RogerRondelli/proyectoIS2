function dismissAlert(mensaje, tipo){
	// Tipos:
	// alert-success
	// alert-info
	// alert-warning
	// alert-danger
	dismiss = "<div class='alert "+tipo+" alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button><strong>"+mensaje+"</strong></div>";
	return dismiss;
}

function alertDismissJS(msj, tipo){
	var salida;
	switch (tipo){
		case 'error':
			salida = "<div class='alert alert-danger alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>"+
			"<span class='glyphicon glyphicon-exclamation-sign'>&nbsp;</span>"+msj+"</div>";
		break;
		
		case 'ok':
			salida = "<div class='alert alert-success alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>"+
			"<span class='glyphicon glyphicon-ok'>&nbsp;</span>"+msj+"</div>";
		break;

		case 'info':
			salida = "<div class='alert alert-primary alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>"+
			"<span class='glyphicon glyphicon-ok'>&nbsp;</span>"+msj+"</div>";
		break;
	}
	return salida; 
}

function fechaMYSQL(fecha){
	//Para el calendario de Jqwidgets
    var fechaArr = fecha.split("/");
    var salida = fechaArr[2]+","+fechaArr[1]+","+fechaArr[0];
	return salida;
}

//Permitir números y puntos (decimales)
function isNumberKey(evt){
	var charCode = (evt.which) ? evt.which : evt.keyCode
	if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
		return false;
	return true;
}

function soloNumeros(evt){
	var charCode = (evt.which) ? evt.which : evt.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;
	return true;
}

//Numeros enteros positivos y negativos (para descuento)
function numerosPositivosNegativos(evt){
	var charCode = (evt.which) ? evt.which : evt.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;
	return true;
}

//cambiar el punto por la coma para valores decimales
function ponerComa(valor){
	resultado = valor.replace(/\./g, ",");
	return resultado
}

//Enter desde el input hace click al button seleccionado
function enterClick(input, button){
	$("#"+input).keyup(function(event){
		if(event.keyCode == 13){
			$("#"+button).click();
			}
	});
}

//Quita todos los tags HTML
function htmlToText(x){
	return x.replace(/<[^>]*>/gi, '');
}

function getDateTime() {
    var now     = new Date(); 
    var year    = now.getFullYear();
    var month   = now.getMonth()+1; 
    var day     = now.getDate();
    var hour    = now.getHours();
    var minute  = now.getMinutes();
    var second  = now.getSeconds(); 
    if(month.toString().length == 1) {
        var month = '0'+month;
    }
    if(day.toString().length == 1) {
        var day = '0'+day;
    }   
    if(hour.toString().length == 1) {
        var hour = '0'+hour;
    }
    if(minute.toString().length == 1) {
        var minute = '0'+minute;
    }
    if(second.toString().length == 1) {
        var second = '0'+second;
    }   
    var dateTime = day+'/'+month+'/'+year+' '+hour+':'+minute+':'+second;   
    return dateTime;
}

//Separacion de miles para guaranies y decimales para dolares
function RemoveRougeChar(convertString, separa){
	if(convertString.substring(0,1) == separa){
		return convertString.substring(1, convertString.length)            
		}
	return convertString;
}

function separadorMiles(x) {
	return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function valorAbsoluto(variable){
	var resultado = 0;
	var valor  = variable.split(",").pop().substring(0,2);
	if (valor == '00') {
		resultado = parseFloat(variable).toFixed(0);
	}else{
		resultado = variable;
	}
	return resultado;
}
function funcion_puntos(variable){
	if (REGION != 1) {
		valor = variable.replace(/\./g, "");
		valor = valor.replace(/\./g, "");
		valor = valor.replace(/\,/g, ".");
		return valor
	}
	return parseInt(variable.replace(/\./g, ""));
}

function operacion_matematica(variable) {
	let valor;
	if (REGION == 2) {
		valor = variable.replace(/\./g, "");
		valor = valor.replace(/\./g, "");
		valor = valor.replace(/\,/g, ".");
		valor = parseFloat(variable).toFixed(2);
	} else {
		valor = parseInt(variable.replace(/\./g, ""));
	}
	return valor
}

function decimalAdjust(type, value, exp) {
	// Si el exp es indefinido o cero...
	if (typeof exp === 'undefined' || +exp === 0) {
		return Math[type](value);
	}
	value = +value;
	exp = +exp;
	// Si el valor no es un número o el exp no es un entero...
	if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
		return NaN;
	}
	// Cambio
	value = value.toString().split('e');
	value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
	// Volver a cambiar
	value = value.toString().split('e');
	return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
}

function generarCodigo(cantidad){
	let resultado = '',
		caracteres = 'ASDFGHJKLZXCVBNMQWERTYUIOPasdfghjklzxcvbnmqwertyuiop0123456789',
		contCaracteres = caracteres.length;

	for (let i = 0; i < cantidad; i++) {
		resultado += caracteres.charAt(Math.floor(Math.random() * contCaracteres));
	}

	return resultado;
}

function proyectos(){
	proyectos = '';
	$.ajax({
		dataType: 'json',
		async: false,
		url: '../server/public/api/proyectos/list',
		type: 'GET',
		success: function (data, status, xhr) {
			console.log('pro',data.data)
			let datos = data.data;
			for (let index = 0; index < datos.length; index++) {
				let element = datos[index];
				console.log(index)
				proyectos += `<option value="`+element['id_proyecto']+`">`+element['nombre']+`</option>`;
			}
			$('#id_proyecto, #id_proyecto_editar').append(proyectos)
		},
		error: function (xhr) {
			console.log(xhr)
			$("#mensaje").html(alertDismissJS("No se pudo completar la operación: " + xhr.status + " " + xhr.statusText, 'error'));
		}

	});
}