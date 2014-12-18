
<?php
	/* ---------------------------------------------------------
	   ---------------------------------------------------------
		Autores:
			- Gonzalo Saavedra Postigo
			- David Mariscal Martínez
			- Aris Burgos Pintos
		Organización: ITelligent Information Technologies, S.L.
		Licencia: gpl-3.0	
	  ----------------------------------------------------------
	  ---------------------------------------------------------- */
?>


<link rel="stylesheet" type="text/css" href="style/border.css">
<link rel="stylesheet" type="text/css" href="style/bordercolors.css">
<link rel="stylesheet" type="text/css" href="style/filter.css">
<link rel="stylesheet" type="text/css" href="style/general.css">
<!-- Scripts for borders -->
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/border.js"></script>  
<script type="text/javascript" src="js/bordercolors.js"></script>  

<script type="text/javascript" src="js/google.js"></script>
<script type="text/javascript" src="js/grafica.ITelligent.js"></script>
<script type="text/javascript" src="js/grafica_layout.ITelligent.js"></script>

<?php


	function parsea($lista){
		$r = "[";
		$t = count($lista);
		for($i=0; $i<$t; $i++){
			$r .= "[";
			$l = count($lista[$i]);
			for($j=0; $j<$l; $j++){
				$dato = $lista[$i][$j];
				if($j==0){
					$dato = '"'.$dato.'"';
				}
				$r .= $dato;
				if($j!=($l-1)){
					$r .= ",";
				}
			}
			$r .= "]";
			if($i!=($t-1)){
				$r .= ",";
			}
		}
		
		return ($r."]");
	}

	function parseaBoolean($b){
		return (($b)?'true':'false');
	}
	
	function parseaString($s){
		return ('"'.$s.'"');
	}

  class graphic {       
  
      // Metodos forma 2 (solo gráficos google según el estándar hablado)
      
      static function draw_perfil_tarea($idCurso) {
        include('include/graphics_perfil_tarea.php');
      }
      
      static function draw_perfil_alumnado($idCurso) {
        include('include/graphics_perfil_alumnado.php');
      }
  
      static function draw_perfil_atributos($idCurso) {
        include('include/graphics_perfil_atributos.php');
      }
          
      // Metodos forma 2 (solo gráficos google según el estándar hablado)
      // $min_valor : valor mínimo del eje que es numérico
      // $max_valor : valor máximo del eje que es numérico 
      // $array_data : valor de los datos del tipo
      //      ['profesor', nota1, nota2, nota3]
      //      ['autoevaluacion', nota1, nota2, nota3]
      //      ......
      // $blnIncluirLimites : incluir limites LS LM LI
      // $blnIncluirDispersion : incluir Dispersión si procediera
      
      static function draw_perfil_tarea_sola($titulo, $min_valor, $max_valor, $array_data, $blnIncluirLimites, $blnIncluirDispersion, $val_lims) 
      {
        
          // Mapear datos obteniendo los promedios y desviaciones típicas
          // ...
        
          // Mostrar resultado
          include('include/graphics_sola.php');
      }                      
  }
?>             
