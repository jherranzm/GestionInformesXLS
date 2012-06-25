/**
 * 
 */

var DEBUG_CONSULTAS = false;

var PANEL = {};

PANEL.formInformeXLS = "#panelFormInformeXLS";
PANEL.listInformeXLS = "#panelListAllInformeXLS";
PANEL.info            = "#panelInfo";
PANEL.infoOK          = "#panelInfoOK";
PANEL.infoError       = "#panelInfoError";

var FIELDS = {}

FIELDS.id 			= "#id";
FIELDS.nombre		= "#nombre";


$(function(){
	
	$(PANEL.infoOK).hide();
	$(PANEL.infoError).hide();
	
	$(PANEL.formInformeXLS).hide();
	
	$("#guardar").button();
	$("#borrar").button();
	$("#buscar").button();
	
	
	$("tr:even").addClass("alt");
	
	$("#formInformeXLS").submit(modInformeXLS);
	$("#formBuscarInformeXLS").submit(buscaInformeXLS);
	
	$(".editInformeXLS").live('click', editInformeXLS);
	$(".delInformeXLS").live('click', delInformeXLS);
	$("#btnAltaInformeXLS").live('click', showFormAltaInformeXLS);
	
	$( "#pestanyesDisponibles, #pestanyesAsignadas" ).sortable({
		connectWith: ".connectedSortable",
		placeholder: "ui-state-highlight",
		receive : function(event,ui){
			//alert("Kaka!!");
			var numPestanyes = $("#pestanyesAsignadas li").length;
			alert("numPestanyes:" + numPestanyes);
			if(numPestanyes < 1){
				alert("Ha de haber como mínimo una pestaña...");
				$(ui.sender).sortable('cancel');
			}
		}
	}).disableSelection();
	
});

/**
 * 
 * @returns {Boolean}
 */
function modInformeXLS(){
	
	var _op = $("#op").val();
	var _id = $("#id").val();
	var _nombre = $("#nombre").val();
	
	if(_op == null || _op == ""){
		alert("Error en el formulario: falta informar la operación.");
		return false;
	}
	
	//Si la operación es modificación, se ha de verificar el id
	if(_op == "updateInformeXLS" && ( _id == null || _id == "")){
		alert("Error en el formulario: operación updateInformeXLS, falta el id.");
		return false;
	}

	if(_nombre == null || _nombre == ""){
		alert("El nombre de la consulta viene sin informar.");
		return false;
	}

	var frm = $(this).serialize();
	
	if(DEBUG_CONSULTAS) alert(frm);
	
	var data = $('#pestanyesAsignadas li').map(function() {
	    return $(this).prop('id');
	}).get();

	$.ajax({
			type: "POST",
			url: "Gestor.InformeXLS.php",
			data: { op : _op,
				nombre : _nombre,
				id : _id,
				pestanyes: data},
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


/**
 * 
 */
function buscaInformeXLS(){
	
	var op = $("#bop").val();
	var nombre = $("#s").val();
	
	if(op == null || op == ""){
		alert("Error en el formulario!");
		return false;
	}
	
	if(nombre == null || nombre == ""){
		alert("El nombre del informe viene sin informar.");
		return false;
	}

	var frm = $(this).serialize();
	
	$.ajax({
			type: "POST",
			url: "Gestor.InformeXLS.php",
			data: frm,
			dataType: "json",
			
			success: function(data){
				var res = "";
				hidePaneles();
				$(PANEL.listInformeXLS).hide().html("");
				if(data.listaInformeXLS.length > 0){
					res = createTableInformesXLS( data.listaInformeXLS );
				}else{
					res = "No se han encontrado registros con nombre similar a "+ nombre;
				}
				$(PANEL.listInformeXLS).html(res).show();
			},
			
			error: function(data){
				alert("ERROR:" + data);
			}
	});
	
	return false;
	
}


/**
 * 
 */
function editInformeXLS(){
	
	$.ajax({
		type: "POST",
		
		url: $(this).prop('href'),
		
		dataType: "json",
		
		success: function(data){
			
			hidePaneles();
			$(FIELDS.id).val(data.listaInformeXLS[0].id);
			$(FIELDS.nombre).val(data.listaInformeXLS[0].nombre);
			
			$("#pestanyesDisponibles").empty();
			for( k = 0; k< data.listaPestanyaDisponible.length; k++ ){
				var d_id = data.listaPestanyaDisponible[k].id;
				var d_nombre = data.listaPestanyaDisponible[k].nombre;
				$("#pestanyesDisponibles").append( "<li id='" +d_id + "'>" + d_nombre + "</li>");
			}
			
			$("#pestanyesAsignadas").empty();
			for( k = 0; k< data.listaInformeXLS[0].listaPestanya.length; k++ ){
				var d_id = data.listaInformeXLS[0].listaPestanya[k].id;
				var d_nombre = data.listaInformeXLS[0].listaPestanya[k].nombre;
				$("#pestanyesAsignadas").append( "<li id='" + d_id + "'>" + d_nombre + "</li>");
			}
			
			
			$("#tituloFormInformeXLS").html("").html("Modificación de InformeXLS");
			$("#op").val("updateInformeXLS");
			$(PANEL.formInformeXLS).show();		
		},
		
		error: function(data){
			alert("ERROR:" + data.codigo+":"+data.mensaje);
		}
	});
	return false;
}



function delInformeXLS(){
	$.ajax({
		type: "POST",
		
		url: $(this).prop('href'),
		
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



function createTableInformesXLS( result ){
	
	var br = "<br/>";
	var iniDiv = "<div>";
	var finDiv = "</div>";
	var iniTable = "<table id='tblInformesXLS' class='tabla'>";
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
	var _controller = "Gestor.InformeXLS.php";
	
	var res = "";
	
		res += iniDiv + "\n";
		res += iniTable + "\n";
		
		res += iniTr + "\n";
		res += iniTh + "Edit" + finTh + "\n";
		res += iniTh + "Borrar" + finTh + "\n";
		res += iniTh + "Nombre" + finTh + "\n";
		res += iniTh + "Número de Pestañas" + finTh + "\n";
		res += finTr + "\n";
		
		$.each(result, function(k, cons) {
			res += iniTr + "\n";
			res += iniTd + "<a class='editInformeXLS' id='edit_" + cons.id + "' href='" + _controller + "?op=edit&id=" + cons.id + "'>" + _img_edit + "</a>" + finTd + "\n";
			res += iniTd + "<a class='delInformeXLS'  id='del_" + cons.id + "' href='" + _controller + "?op=del&id=" + cons.id + "'>" + _img_delete + "</a>" + finTd + "\n";
			res += iniTd + cons.nombre + finTd + "\n";
			res += iniTd + cons.numPestanyes + finTd + "\n";
			res += finTr + "\n";
		});
		res += finTable + "\n";
		res += finDiv + "\n";
		
	return res;


}

function showFormAltaInformeXLS(){
	
	hidePaneles();
	$("#tituloFormInformeXLS").html("").html("Alta de InformeXLS");
	$("#pestanyesDisponibles").empty();
	// Rellenar todas las pestañas disponibles
	getPestanyesDisponibles();
	$("#pestanyesAsignadas").empty();
	$("#pestanyesAsignadas").append( "<li id='00' class='defOption'>Arrastrar aquí!</li>");
	
	$("#nombre").val("");
	$("#id").val("");
	$("#op").val("createInformeXLS");
	$(PANEL.formInformeXLS).show();
}


function hidePaneles(){
	
	$(PANEL.infoOK).hide();
	$(PANEL.infoError).hide();
	$(PANEL.listInformeXLS).hide();
	$(PANEL.formInformeXLS).hide();
}


function getPestanyesDisponibles(){
	
	$.ajax({
		type: "POST",
		url: "Gestor.Pestanya.php",
		data: { 
			op : "getAll"
			},
		dataType: "json",
		
		success: function(data){
			
			$("#pestanyesDisponibles").empty();
			for( k = 0; k< data.listaPestanyaDisponible.length; k++ ){
				var d_id = data.listaPestanyaDisponible[k].id;
				var d_nombre = data.listaPestanyaDisponible[k].nombre;
				$("#pestanyesDisponibles").append( "<li id='" + d_id + "'>" + d_nombre + "</li>");
			}
		},
		
		error: function(data){
			alert("ERROR:" + data.codigo+":"+data.mensaje);
		}
	});
	return false;
}