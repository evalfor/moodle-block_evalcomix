<?php

/*
	Autores:
		- Gonzalo Saavedra Postigo
		- David Mariscal Martínez
		- Aris Burgos Pintos
	Organización: ITelligent Information Technologies, S.L.
	Licencia: gpl-3.0
*/

echo '<script type="text/javascript">
		if(window.addEventListener){
		  window.addEventListener(\'load\',function(){
			graf = new Grafica (\'LineChart\',document.getElementById(\'grafica\'),document.getElementById(\'tdGraficas\')); 
			graf.generargraficaLine ('.parseaString($titulo).',
									 '.$min_valor.',
									 '.$max_valor.',
									 '.parsea($array_data).', 
									 '.parseaBoolean($blnIncluirLimites).',
									 '.parseaBoolean($blnIncluirDispersion).',
									 '.$val_lims.');
		  },true);
		}
		else{
			if (window.attachEvent){   
				window.attachEvent(\'onload\', function(){
				graf = new Grafica (\'LineChart\',document.getElementById(\'grafica\'),document.getElementById(\'tdGraficas\')); 
				graf.generargraficaLine ('.parseaString($titulo).',
													 '.$min_valor.',
													 '.$max_valor.',
													 '.parsea($array_data).', 
													 '.parseaBoolean($blnIncluirLimites).',
													 '.parseaBoolean($blnIncluirDispersion).',
													 '.$val_lims.');
						  });
				}  
		}  
		
		</script>';
?>

<script type="text/javascript">
document.getElementById('pstPT').className ="pestania";
document.getElementById('pstPA').className ="pestania";
document.getElementById('pstPAtr').className ="pestania";
document.getElementById('pstPAleat').className ="pestania_activa";
</script>

<table style="background-color: transparent; display: inline;">
		<tr id="tdGraficas" style="width: 100%; background-color: transparent; vertical-align: top;">
			<div id="graficas" style="width: 100%; text-align: center; display: inline;" class="shadowbox">
				<div id="grafica" style="text-align: center;" ></div>
			</div>
		</tr>			
 </table>  

