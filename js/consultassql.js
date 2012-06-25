/**
 * 
 */

var DEBUG_CONSULTAS = false;

var PANEL = {};

PANEL.altaConsultaSQL = "#panelInsertConsultaSQL";
PANEL.editConsultaSQL = "#panelUpdateConsultaSQL";
PANEL.listConsultaSQL = "#panelListAllConsultaSQL";
PANEL.info            = "#panelInfo";
PANEL.infoOK          = "#panelInfoOK";
PANEL.infoError       = "#panelInfoError";

var FIELDS = {}

FIELDS.altaNombre = "#iNombre";
FIELDS.altaConsultaSQL = "#iConsultaSQL";

FIELDS.editNombre = "#uNombre";
FIELDS.editConsultaSQL = "#uConsultaSQL";
FIELDS.editId = "#uid";


$(function(){
	
	$(PANEL.infoOK).hide();
	$(PANEL.infoError).hide();
	
	$(PANEL.editConsultaSQL).hide();
	$(PANEL.altaConsultaSQL).hide();
	
	$("#iGuardar").button();
	$("#uGuardar").button();
	$("#iBorrar").button();
	$("#uBorrar").button();
	$("#buscar").button();
	
	
	$("tr:even").addClass("alt");
	
	$("#formInsertConsultaSQL").submit(altaConsultaSQL);
	$("#formUpdateConsultaSQL").submit(modConsultaSQL);
	$("#formBuscarConsultaSQL").submit(buscaConsultaSQL);
	
	$(".editConsultaSQL").live('click', editConsultaSQL);
	$(".delConsultaSQL").live('click', delConsultaSQL);
	$("#btnAltaConsultaSQL").live('click', showFormAltaConsultaSQL);
	
	
});

/**
 * 
 * @returns {Boolean}
 */
function altaConsultaSQL(){
	
	var op = $("#iop").val();
	var nombre = $("#iNombre").val();
	var consultaSQL = $("#iConsultaSQL").val();
	
	if(op == null || op == ""){
		alert("Error en el formulario!");
		return false;
	}
	
	if(nombre == null || nombre == ""){
		alert("El nombre de la consulta viene sin informar.");
		return false;
	}

	if(consultaSQL == null || consultaSQL == ""){
		alert("La definición de la consulta viene sin informar.");
		return false;
	}
	
	var frm = $(this).serialize();
	
	if(DEBUG_CONSULTAS) alert(frm);
	

	$.ajax({
			type: "POST",
			url: "Gestor.ConsultaSQL.php",
			data: frm,
			dataType: "text",
			
			success: function(data){
				//alert("BIEN:" + data);
				hidePaneles();
				var msg = "Alta correctamente realizada!";
				$("#infoText").html(msg);
				$(PANEL.infoOK).show();
			},
			
			error: function(data){
				alert("ERROR:" + data);
			}
	});
	
	return false;
	
}

/**
 * 
 * @returns {Boolean}
 */
function modConsultaSQL(){
	
	var op = $("#uop").val();
	var id = $("#uid").val();
	var nombre = $("#uNombre").val();
	var consultaSQL = $("#uConsultaSQL").val();
	
	if(op == null || op == ""){
		alert("Error en el formulario!");
		return false;
	}
	
	if(id == null || id == ""){
		alert("Error en el formulario!");
		return false;
	}

	if(nombre == null || nombre == ""){
		alert("El nombre de la consulta viene sin informar.");
		return false;
	}

	if(consultaSQL == null || consultaSQL == ""){
		alert("La definición de la consulta viene sin informar.");
		return false;
	}
	
	var frm = $(this).serialize();
	
	if(DEBUG_CONSULTAS) alert(frm);
	

	$.ajax({
			type: "POST",
			url: "Gestor.ConsultaSQL.php",
			data: frm,
			dataType: "json",
			
			success: function(data){
				hidePaneles();
				var msg = "Modificación correctamente realizada!";
				$("#infoText").html(msg);
				$(PANEL.infoOK).show();
			},
			
			error: function(data){
				alert("ERROR:" + data);
			}
	});
	
	return false;
	
}



function buscaConsultaSQL(){
	
	var op = $("#bop").val();
	var nombre = $("#s").val();
	
	if(op == null || op == ""){
		alert("Error en el formulario!");
		return false;
	}
	
	if(nombre == null || nombre == ""){
		alert("El nombre de la consulta viene sin informar.");
		return false;
	}

	var frm = $(this).serialize();
	
	$.ajax({
			type: "POST",
			url: "Gestor.ConsultaSQL.php",
			data: frm,
			dataType: "json",
			
			success: function(data){
				var res = "";
				hidePaneles();
				$(PANEL.listConsultaSQL).hide().html("");
				if(data.listaConsultaSQL.length > 0){
					res = createTableConsultas( data.listaConsultaSQL );
				}else{
					res = "No se han encontrado registros con nombre similar a "+ nombre;
				}
				$(PANEL.listConsultaSQL).html(res).show();
			},
			
			error: function(data){
				alert("ERROR:" + data);
			}
	});
	
	return false;
	
}



function editConsultaSQL(){
	
	//alert($(this).attr('href'));
	$.ajax({
		type: "POST",
		
		url: $(this).attr('href'),
		
		dataType: "json",
		
		success: function(data){
			
			hidePaneles();
			$(FIELDS.editId).val(data.id);
			$(FIELDS.editNombre).val(data.nombre);
			$(FIELDS.editConsultaSQL).val(data.definicion);
			
			$(PANEL.editConsultaSQL).show();		
		},
		
		error: function(data){
			alert("ERROR:" + data.id+":"+data.nombre);
		}
	});
	return false;
}



function delConsultaSQL(){
	$.ajax({
		type: "POST",
		
		url: $(this).attr('href'),
		
		dataType: "json",
		
		success: function(respuesta){
			
			hidePaneles();
			if(respuesta.codigo == "00"){
				$("#infoText").html(respuesta.mensaje);
				$(PANEL.infoOK).show();
				
			}else{
				$("#infoError").html(respuesta.mensaje);
				$(PANEL.infoError).show();
				
			}
		},
		
		error: function(data){
			alert("ERROR:" + data.id+":"+data.nombre);
		}
	});
	return false;
}



function createTableConsultas( result ){
	
	var br = "<br/>";
	var iniDiv = "<div>";
	var finDiv = "</div>";
	var iniTable = "<table id='tblConsultasSQL' class='tabla'>";
	var finTable = "</table>";
	var iniTr = "<tr>";
	var finTr = "</tr>";
	var iniTh = "<th>";
	var finTh = "</th>";
	var iniTd = "<td>";
	var iniTdOp = "<td class='operation'>";
	var finTd = "</td>";
	
	var _img_delete = "<img src='img/Png/Delete.png'>";
	var _img_edit   = "<img src='img/Png/Application-Edit.png'>";
	var _controller = "Gestor.ConsultaSQL.php";
	
	var res = "";
	
//	if(result.listaConsultaSQL.length >0){
		res += iniDiv + "\n";
		res += iniTable + "\n";
		
		res += iniTr + "\n";
		res += iniTh + "Edit" + finTh + "\n";
		res += iniTh + "Borrar" + finTh + "\n";
		res += iniTh + "Nombre" + finTh + "\n";
		res += iniTh + "Definicion" + finTh + "\n";
		res += finTr + "\n";
		
		$.each(result, function(k, cons) {
			res += iniTr + "\n";
			res += iniTd + "<a class='editConsultaSQL' id='edit_" + cons.id + "' href='" + _controller + "?op=edit&id=" + cons.id + "'>" + _img_edit + "</a>" + finTd + "\n";
			res += iniTd + "<a class='delConsultaSQL'  id='del_" + cons.id + "' href='" + _controller + "?op=del&id=" + cons.id + "'>" + _img_delete + "</a>" + finTd + "\n";
			res += iniTd + cons.nombre + finTd + "\n";
			res += iniTd + cons.definicion + finTd + "\n";
			res += finTr + "\n";
			//echo $cons->toString();
			//alert("**" + $cons->toString() + "**");
		});
		res += finTable + "\n";
		res += finDiv + "\n";
		
//	}
	
	return res;


}

function showFormAltaConsultaSQL(){
	//$.each(PANEL, function(index, value){ $(value).hide();});
	
	hidePaneles();
	$(PANEL.altaConsultaSQL).show();
}


function hidePaneles(){
	//$.each(PANEL, function(index, value){ $(value).hide();});
	
	$(PANEL.infoOK).hide();
	$(PANEL.infoError).hide();
	$(PANEL.listConsultaSQL).hide();
	$(PANEL.editConsultaSQL).hide();
	$(PANEL.altaConsultaSQL).hide();
}