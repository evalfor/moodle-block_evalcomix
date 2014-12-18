<?php

/*
	Autores:
		- Gonzalo Saavedra Postigo
		- David Mariscal Martínez
		- Aris Burgos Pintos
	Organización: ITelligent Information Technologies, S.L.
	Licencia: gpl-3.0
*/

?>
	<input id="idCurso" style="visibility:hidden;" hidden="true" value="<?php echo $idCurso ?>">

	<div class="filter">		  
	  <div id="divtarea" class="campo" style="display: none;"> Tarea: 
	  <select id="cmbxtarea" name="cmbxtarea" size="1" onChange="cambio_filtro_tarea();"></select> 	  
	  </div>
	</div>   
	  
	
	<div class="filter">
		<div id="divtipografica" class="campo" style="display: none;">
			<input id="rbalumno" type="radio" name="tipocalificacion" value="Alumno" onclick="cambio_radiobutton(4);"><label id="divtipoalumno"> Alumno </label> 
			<input id="rbgrupo" type="radio" name="tipocalificacion" value="Grupo" onclick="cambio_radiobutton(2);"><label id="divtipogrupo"> Grupo </label> 
			<input id="rbclase" type="radio" name="tipocalificacion" value="Clase" onclick="cambio_radiobutton();"><label id="divtipoclase"> Clase </label> 
		</div> 
	</div>   
	  
	<div class="filter">
	  <div id="divtipografica2" class="campo" style="display: none;">
		  <input id="rbprofesor" type="radio" name="tipocalificacion2" value="Alumno" onclick="cambio_radiobutton(4);"><label id="divtipoprofesor"> Profesor </label> 
		  <input id="rbei" type="radio" name="tipocalificacion2" value="Grupo" onclick="cambio_radiobutton(4);"><label id="divtipoei"> Entre iguales </label>   
	  </div>
	</div>   
	  	
	<div class="filter">
	  
	  <div id="divmodalidad" class="campo" style="display: none;"> Modalidad: 
	  <select id="cmbxmodalidad" name="cmbxmodalidad" size="1" onChange="cambio_filtro_modalidad ()"></select> 	  
	  </div>
	</div>   
	  
	<div class="filter">	  
	  <div id="divgrupo" class="campo" style="display: none;"> Grupo: 
	  <select id="cmbxgrupo" name="cmbxgrupo" size="1" onChange="cambio_filtro_grupo ();"></select> 	  
	  </div>  
	</div> 
	  
	<div class="filter">
	  <div id="divalumno" class="campo" style="display: none;"> Alumno: 
	  <select id="cmbxalumno" name="cmbxalumno" size="1" onChange="cambio_filtro_alumno ();"></select> 
	  
	  </div>
	</div>   
	  
	<div class="filter">	   
		<div id="divalumnos" style="display: none;">
			<ul id="ulalumnos" class="checklist campo" style="padding: 5px; margin: 5px; width: 230px;">                        				
			</ul>
		</div> 
	</div>   	  	  	 	
  
  <ul id="ullimite" class="campo" style="display: none;">
  <li style="display:inline"> <label><input id="chkbxlimite" name="limite" type="checkbox" onClick="cambio_filtro_limites(); mostrar_grafica();" /> Limite?</label></li>
  <li style="display:inline"> <select id="cmbxlimite" name="cmbxlimite" size=1 onChange="cambio_filtro_limites_k(); mostrar_grafica();">
    <option value="1">68,27%</option>
    <option value="2">95,45%</option>
    <option value="3">99,73%</option>
  </select> </li>
  </ul>
  
  <ul id="uldispersion" class="campo" style="display: none;">
  <li style="display:inline"> <label><input id="chkbxdispersion" name="dispersion" type="checkbox" onClick="cambio_filtro_dispersion(); mostrar_grafica();" /> Ver dispersión?</label></li>  
  </ul>