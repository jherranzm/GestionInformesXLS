<?php 
require_once('ConsultaSQL.php');
require_once('ConsultaSQLService.php');


header('Content-Type: text/html; charset=utf-8');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Creando ConsultasSQL</title>
<link rel="stylesheet" type="text/css" media="all" href="css/reset.css" />
<link rel="stylesheet" type="text/css" media="all" href="css/text.css" />
<link rel="stylesheet" type="text/css" media="all" href="css/960.css" />

<link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.8.20.custom.css" />	

<link rel="stylesheet" type="text/css" media="all" href="css/consultassql.css" />

<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.20.custom.min.js"></script>
<script type="text/javascript" src="js/consultassql.js"></script>
</head>
<body>

	<div class="container_12">
		<div class="grid_12">
			<div>
				<h2>Creando ConsultasSQL</h2>
			</div>
		</div>

		<div id="panelMenuConsultaSQL" class="grid_12 ui-corner-all regs-panel">
			<div id="panelBuscarConsultaSQL" class="grid_1 alpha">
				<img src="img/Png/Add.png" id="btnAltaConsultaSQL" alt="Alta de Consulta">
			</div>
			<div id="panelBuscarConsultaSQL" class="grid_7">
					<form id="formBuscarConsultaSQL" method="post"  action="#">
						<input type="text" id="s" name="s"  class="ui-widget ui-widget-content"/>
						<input type="submit" id="buscar" name="buscar" value="Buscar">
						<input type="hidden" id="bop" name="op" value="searchConsultaSQL">
					</form>
			</div>
			<div id="panelGoToInformesXLS" class="grid_2">
				<a href="vInformesXLS.php">Gestión Informes XLS</a>
			</div>
			<div id="panelGoToPestanyas" class="grid_2 omega">
				<a href="vPestanya.php">Gestión Pestañas</a>
			</div>
		</div> <!-- end .grid_12 -->
		<div class="clear"></div>
		
		<div id="panelInfo" class="grid_12 ui-corner-all">
		
			<div id="panelInfoOK" class="grid_12 alpha ui-state-highlight">
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span> 
				<strong>Info:</strong> <span id="infoText"></span></p>
			</div>
			
			
			<div id="panelInfoError" class="grid_12 alpha ui-state-error">
				<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 
				<strong>Error:</strong> <span id="errorText"></span></p>
			</div>
		</div> <!-- end .grid_12 -->
		<div class="clear"></div>
		
		
		
		<div id="panelInsertConsultaSQL" class="grid_12 ui-corner-all regs-panel">
			<div>
			
				<h3>Alta de ConsultaSQL</h3>
				<form id="formInsertConsultaSQL" method="post" action="#">
	
					<label for="iNombre">Nombre</label> 
					<input type="text" id="iNombre" name="nombre"  class="ui-widget ui-widget-content"/> <br /> 
					
					<label for="iConsultaSQL">SQL</label> <br />
					<textarea id="iConsultaSQL" name="consultaSQL" 
						 class="ui-widget ui-widget-content" rows="10" cols="100"></textarea>
					<br /> 
					<input type="submit" id="iGuardar" name="guardar" value="Guardar"> 
					<input type="reset" id="iBorrar" name="borrar" value="Borrar">
					
					<input type="hidden" id="iop" name="op" value="createConsultaSQL"> 
	
				</form><!-- end #formInsertConsultaSQL -->
				
			</div><!-- end #panelInsertConsultaSQL -->
			
		</div> <!-- end .grid_12 -->
		<div class="clear"></div>

		
		<div id="panelUpdateConsultaSQL" class="grid_12 ui-corner-all regs-panel">
			<div>
			
				<h3>Modifica ConsultaSQL</h3>
				<form id="formUpdateConsultaSQL" method="post" action="#">
	
					<label for="uNombre">Nombre</label> 
					<input type="text" id="uNombre" name="nombre"  size="100" class="ui-widget ui-widget-content"/> <br /> 
					
					<label for="uConsultaSQL">SQL</label> <br />
					<textarea id="uConsultaSQL" name="consultaSQL" 
						 class="ui-widget ui-widget-content" rows="10" cols="100"></textarea>
					<br /> 
					<input type="submit" id="uGuardar" name="guardar" value="Guardar"> 
					<input type="reset" id="uBorrar" name="borrar" value="Borrar">
					
					<input type="hidden" id="uop" name="op" value="updateConsultaSQL"> 
					<input type="hidden" id="uid" name="id" value="">
	
				</form><!-- end #formInsertConsultaSQL -->
				
			</div><!-- end #panelInsertConsultaSQL -->
			
		</div> <!-- end .grid_12 -->
		<div class="clear"></div>
		
		
		
		<div class="grid_12">
			<div id="panelListAllConsultaSQL">
			<!-- vacio -->
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<!-- end .container_12 -->



</body>
</html>
