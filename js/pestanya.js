/**
 * 
 */

var DEBUG_CONSULTAS = false;

var PANEL = {};

PANEL.formPestanya = "#panelFormPestanya";
PANEL.listPestanya = "#panelListAllPestanya";
PANEL.info            = "#panelInfo";
PANEL.infoOK          = "#panelInfoOK";
PANEL.infoError       = "#panelInfoError";

var FIELDS = {}

FIELDS.id 			= "#id";
FIELDS.nombre		= "#nombre";
FIELDS.rango		= "#rango";


$(function(){
	
	$(PANEL.infoOK).hide();
	$(PANEL.infoError).hide();
	
	$(PANEL.formPestanya).hide();
	
	$("#guardar").button();
	$("#borrar").button();
	$("#buscar").button();
	
	
	$("tr:even").addClass("alt");
	
	$("#formPestanya").submit(modPestanya);
	$("#formBuscarPestanya").submit(buscaPestanya);
	
	$(".editPestanya").live('click', editPestanya);
	$(".delPestanya").live('click', delPestanya);
	$("#btnAltaPestanya").live('click', showFormAltaPestanya);
	
	
});

/**
 * 
 * @returns {Boolean}
 */
function modPestanya(){
	
	var op = $("#op").val();
	var id = $("#id").val();
	var nombre = $("#nombre").val();
	var rango = $("#rango").val();
	var numFilaInicial = $("#numFilaInicial").val();
	var consultaSQLid = $("#consultaSQLid").val();
	
	if(op == null || op == ""){
		alert("Error en el formulario: falta informar la operación.");
		return false;
	}
	
	//Si la operación es modificación, se ha de verificar el id
	if(op == "updatePestanya" && ( id == null || id == "")){
		alert("Error en el formulario: operación updatePestanya, falta el id.");
		return false;
	}

	if(nombre == null || nombre == ""){
		alert("El nombre de la consulta viene sin informar.");
		return false;
	}

	if(rango == null || rango == ""){
		alert("El nombre del rango viene sin informar.");
		return false;
	}

	if(numFilaInicial == null || numFilaInicial == ""){
		alert("El número de la fila inicial viene sin informar.");
		return false;
	}

	if(consultaSQLid == null || consultaSQLid == ""){
		alert("La consulta viene sin informar.");
		return false;
	}
	
	var frm = $(this).serialize();
	
	if(DEBUG_CONSULTAS) alert(frm);
	

	$.ajax({
			type: "POST",
			url: "Gestor.Pestanya.php",
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



function buscaPestanya(){
	
	var op = $("#bop").val();
	var nombre = $("#s").val();
	
	if(op == null || op == ""){
		alert("Error en el formulario!");
		return false;
	}
	
	if(nombre == null || nombre == ""){
		alert("El nombre de la pestaña viene sin informar.");
		return false;
	}

	var frm = $(this).serialize();
	
	$.ajax({
			type: "POST",
			url: "Gestor.Pestanya.php",
			data: frm,
			dataType: "json",
			
			success: function(data){
				var res = "";
				hidePaneles();
				$(PANEL.listPestanya).hide().html("");
				if(data.listaPestanya.length > 0){
					res = createTableConsultas( data.listaPestanya );
				}else{
					res = "No se han encontrado registros con nombre similar a "+ nombre;
				}
				$(PANEL.listPestanya).html(res).show();
			},
			
			error: function(data){
				alert("ERROR:" + data);
			}
	});
	
	return false;
	
}



function editPestanya(){
	
	//alert($(this).attr('href'));
	$.ajax({
		type: "POST",
		
		url: $(this).attr('href'),
		
		dataType: "json",
		
		success: function(data){
			
			hidePaneles();
			$(FIELDS.id).val(data.listaPestanya[0].id);
			$(FIELDS.nombre).val(data.listaPestanya[0].nombre);
			$(FIELDS.rango).val(data.listaPestanya[0].rango);
			
			var str = "";
			for(k=0; k<41; k++){
				if(k == data.listaPestanya[0].numfilainicial){
					$("#numFilaInicial").append($("<option/>").val(k).html(k).attr("selected", "selected"));
				}else{
					$("#numFilaInicial").append($("<option/>").val(k).html(k));
				}
			}
			
			str = "";
			for(j=0; j<data.listaConsultaSQL.length; j++){
				if(data.listaPestanya[0].consultaid == data.listaConsultaSQL[j].id){
					$("#consultaSQLid").append($("<option/>").val(data.listaConsultaSQL[j].id).html(data.listaConsultaSQL[j].nombre).attr("selected", "selected"));
				}else{
					$("#consultaSQLid").append($("<option/>").val(data.listaConsultaSQL[j].id).html(data.listaConsultaSQL[j].nombre));
				}
			}
			
			$("#tituloFormPestanya").html("").html("Modificación de Pestanya");
			$("#op").val("updatePestanya");
			$(PANEL.formPestanya).show();		
		},
		
		error: function(data){
			alert("ERROR:" + data.id+":"+data.nombre);
		}
	});
	return false;
}



function delPestanya(){
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
	var _controller = "Gestor.Pestanya.php";
	
	var res = "";
	
//	if(result.listaPestanya.length >0){
		res += iniDiv + "\n";
		res += iniTable + "\n";
		
		res += iniTr + "\n";
		res += iniTh + "Edit" + finTh + "\n";
		res += iniTh + "Borrar" + finTh + "\n";
		res += iniTh + "Nombre" + finTh + "\n";
		res += iniTh + "Rango" + finTh + "\n";
		res += iniTh + "Fila Inicial" + finTh + "\n";
		res += iniTh + "Nombre Consulta" + finTh + "\n";
		res += finTr + "\n";
		
		$.each(result, function(k, cons) {
			res += iniTr + "\n";
			res += iniTd + "<a class='editPestanya' id='edit_" + cons.id + "' href='" + _controller + "?op=edit&id=" + cons.id + "'>" + _img_edit + "</a>" + finTd + "\n";
			res += iniTd + "<a class='delPestanya'  id='del_" + cons.id + "' href='" + _controller + "?op=del&id=" + cons.id + "'>" + _img_delete + "</a>" + finTd + "\n";
			res += iniTd + cons.nombre + finTd + "\n";
			res += iniTd + cons.rango + finTd + "\n";
			res += iniTd + cons.numfilainicial + finTd + "\n";
			res += iniTd + cons.nombreConsulta + finTd + "\n";
			res += finTr + "\n";
			//echo $cons->toString();
			//alert("**" + $cons->toString() + "**");
		});
		res += finTable + "\n";
		res += finDiv + "\n";
		
//	}
	
	return res;


}

function showFormAltaPestanya(){
	
	hidePaneles();
	$("#tituloFormPestanya").html("").html("Alta de Pestanya");
	$("#op").val("createPestanya");
	$(PANEL.formPestanya).show();
}


function hidePaneles(){
	
	$(PANEL.infoOK).hide();
	$(PANEL.infoError).hide();
	$(PANEL.listPestanya).hide();
	$(PANEL.formPestanya).hide();
}