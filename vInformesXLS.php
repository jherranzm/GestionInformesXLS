<?php 
require_once('Pestanya.php');
require_once('PestanyaService.php');
require_once('ConsultaSQL.php');
require_once('ConsultaSQLService.php');


header('Content-Type: text/html; charset=utf-8');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Creando Informes XLS</title>
<link rel="stylesheet" type="text/css" media="all" href="css/reset.css" />
<link rel="stylesheet" type="text/css" media="all" href="css/text.css" />
<link rel="stylesheet" type="text/css" media="all" href="css/960.css" />

<link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.8.20.custom.css" />	

<link rel="stylesheet" type="text/css" media="all" href="css/consultassql.css" />

<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.20.custom.min.js"></script>
<script type="text/javascript" src="js/informeXLS.js"></script>
</head>
<body>
	<div class="container_12">
	
		<div class="grid_12">
			<div>
				<h2>Creando Informes</h2>
			</div>
		</div>
		
		<div id="panelMenuInformeXLS" class="grid_12 ui-corner-all regs-panel">
			<div id="panelBuscarInformeXLS" class="grid_1 alpha">
				<img src="img/Png/Add.png" id="btnAltaInformeXLS" alt="Alta de Consulta">
			</div>
			<div id="panelBuscarInformeXLS" class="grid_7">
					<form id="formBuscarInformeXLS" method="post"  action="#">
						<input type="text" id="s" name="s"  class="ui-widget ui-widget-content"/>
						<input type="submit" id="buscar" name="buscar" value="Buscar">
						<input type="hidden" id="bop" name="op" value="searchInformeXLS">
					</form>
			</div>
			<div id="panelGoToConsultasSQL" class="grid_2">
				<a href="vConsultaSQL.php">Gestión Consultas SQL</a>
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
		
		
		
		<div id="panelFormInformeXLS" class="grid_12 ui-corner-all regs-panel">
			<div>
			
				<h3 id="tituloFormInformeXLS">Alta de InformeXLS</h3>
				<form id="formInformeXLS" method="post" action="#">
	
					<label for="nombre">Nombre</label> 
					<input type="text" id="nombre" name="nombre" class="ui-widget ui-widget-content"/> <br /> 
										
					<div id="left" class="grid_5 ui-corner-all alpha">
						<p>Pestañas Disponibles</p><br>
						<ul id="pestanyesDisponibles" class="connectedSortable">
						</ul>
					</div>

					<div id="right" class="grid_5 ui-corner-all omega">
						<p>Pestañas Asignadas</p><br>
						<ol id="pestanyesAsignadas" class="connectedSortable">
						</ol>
					</div>
					<div class="clear"></div>
					
					<label for="comentario">Comentario</label> <br />
					<textarea id="comentario" class="ui-widget ui-widget-content" 
						name="comentario" rows="10" cols="100"></textarea>
					<br /> 
					<input type="submit" id="guardar" name="guardar" value="Guardar"> 
					<input type="reset" id="borrar" name="borrar" value="Borrar">
					
					<input type="hidden" id="op" name="op" value="createInformeXLS"> 
					<input type="hidden" id="id" name="id" value=""> 
	
				</form><!-- end #formInformeXLS -->
				
			</div><!-- end #panelInsertInformeXLS -->
			
		</div> <!-- end .grid_12 -->
		<div class="clear"></div>

		
		
		<div class="grid_12">
			<div id="panelListAllInformeXLS">
			<!-- vacio -->
			</div>
		</div>
		<div class="clear"></div>
		
	</div>
</body>
</html>