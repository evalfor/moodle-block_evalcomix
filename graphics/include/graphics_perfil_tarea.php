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

<script type="text/javascript">
document.getElementById('pstPT').className ="pestania_activa";
document.getElementById('pstPA').className ="pestania";
document.getElementById('pstPAtr').className ="pestania";
document.getElementById('pstPAleat').className ="pestania";

	if(window.addEventListener){
	  window.addEventListener('load',function(){
		graf = new Grafica ('ImageChart',document.getElementById('grafica'),document.getElementById('tdGraficas')); 
		init(); 
		inicializarFiltros('graficaperfiltarea')
	  },true); 
	}
	else{
		if (window.attachEvent){   
			window.attachEvent('onload', function(){
			graf = new Grafica ('ImageChart',document.getElementById('grafica'),document.getElementById('tdGraficas')); 
			init(); 
			inicializarFiltros('graficaperfiltarea')
			});   
		}  
	}
</script>

 <table style="background-color: transparent; display: inline;">
	<tr>
		<td style="width: 250px; max-width: 350px; vertical-align: top; ">
			<?php include('include/filtros.php');?>
		</td>
		<td id="tdGraficas" style="width: 100%; background-color: transparent; vertical-align: top;">
			<div id="graficas" style="width: 100%; text-align: center; display: none;" class="shadowbox">
				<div id="grafica" style="text-align: center;" ></div>
			</div>
		</td>		
	</tr>
  </table>      

