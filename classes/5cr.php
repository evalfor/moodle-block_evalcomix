<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005 Julian Andrés Lasso Figueroa                      |
// +----------------------------------------------------------------------+
// | Este codigo está sujeto a la licencia GPL, cualquier bug encontrado  |
// | por favor avisarme con un correo a julian_lasso@yahoo.com.mx         |
// | Este codigo está basado en RC4 v 1.6                                 |
// +----------------------------------------------------------------------+
// | Autor: Julian Andrés Lasso Figueroa <julian_lasso@yahoo.com.mx>      |
// +----------------------------------------------------------------------+
// 5CR.php, v 2.0 09/05/2005 10:50:00 p.m.

class E5CR{
	
	var $s = array();
  var $i = 0;
  var $j = 0;
  var $_key;
  var $bytes = 256;
  
  function E5CR($key = null)
  {
    if ($key != null) {
      $this->juego_de_llaves($key);
    } 
  }
  
  function juego_de_llaves($key)
  {
    if (strlen($key) > 0)
      $this->_key = $key;
  }

  function llave(&$key)
  {
    $len = strlen($key);
    for ($this->i = 0; $this->i < $this->bytes; $this->i++) {
      $this->s[$this->i] = $this->i;
    } 

    $this->j = 0;
    for ($this->i = 0; $this->i < $this->bytes; $this->i++) {
      $this->j = ((($this->j + $this->s[$this->i] + ord($key[$this->i % $len])) % $this->bytes) / 3);
      $this->j += ((($this->j + $this->s[$this->i] + ord($key[$this->i % $len])) % $this->bytes) % 12);
      $t = $this->s[$this->i];
      $this->s[$this->i] = $this->s[$this->j];
      $this->s[$this->j] = $t;
    } 
    $this->i = $this->j = 0;
  }
  
  function hex2bin(&$RawInput)
  {
		$BinStr = '';
		for ($i = 0; $i < strlen ($RawInput); $i += 2)
			$BinStr .= '%'.substr ($RawInput, $i, 2);
		$RawInput = rawurldecode($BinStr);
	}

  function encriptar(&$encript, $tipo)
  {
	$key = null;
  	$this->llave($this->_key);
		$len = strlen($encript);
		for ($c = 0; $c < $len; $c++) {
			$this->i = ((($this->i + 1) % $this->bytes) / 3) + ((($this->j + $this->s[$this->i] + ord($key[$this->i % $len])) % $this->bytes) % 12);
		  $this->j = ((($this->j + $this->s[$this->i]) % $this->bytes) / 3) + ((($this->j + $this->s[$this->i] + ord($key[$this->i % $len])) % $this->bytes) % 12);
		  $t = $this->s[$this->i];
		  $this->s[$this->i] = $this->s[$this->j];
		  $this->s[$this->j] = $t;
		
		  $t = ((($this->s[$this->i] + $this->s[$this->j]) % $this->bytes) / 3) + ((($this->j + $this->s[$this->i] + ord($key[$this->i % $len])) % $this->bytes) % 12);
		
		  $encript[$c] = chr(ord($encript[$c]) ^ $this->s[$t]);
		}
		
		// 0 = claves y 1 = url
  	switch($tipo){
  		case 0:
		    $encript = crc32(sha1(md5(bin2hex($encript))));
		    break;
		  case 1:
		  	$encript = bin2hex($encript);
		  case 2:
		  	$encript;
  	}
  }
  
  function destrozar(&$cadena, $num){
		for($i=0;$i<$num;$i++){
			$pos = strpos($cadena,"="); // posición del primer =
			$campo = substr($cadena,0,$pos); // sustración del nombre del campo
			$cadena = substr($cadena,$pos+1,strlen($cadena)); // actualización de la cadena
			$pos = strpos($cadena,"&"); // posición del primer &
			if($pos == null)
				$pos = strlen($cadena);
			$contenido = substr($cadena,0,$pos);
			$cadena = substr($cadena,$pos+1,strlen($cadena)); // actualización de la cadena
			$datos[$campo] = $contenido;
			
		}
		return $datos;
  }
  
  function desencriptar(&$desencript, $num)
  {
  	$this->hex2bin($desencript);
    $this->encriptar($desencript,2);
	 	$desencript = $this->destrozar($desencript, $num);
  }
}
?>
