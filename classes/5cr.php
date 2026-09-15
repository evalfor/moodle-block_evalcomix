<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * File for block_evalcomix_E5CR
 *
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */

/**
 * Class for block_evalcomix_E5CR
 *
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */
class block_evalcomix_E5CR {
    /**
     * @var array $s
     */
    public $s = [];

    /**
     * @var int $i
     */
    public $i = 0;

    /**
     * @var int $j
     */
    public $j = 0;

    /**
     * @var string $_key
     */
    public $_key;

    /**
     * @var int $bytes
     */
    public $bytes = 256;

    /**
     * Construct
     *
     * @param $key
     */
    public function __construct($key = null) {
        if ($key != null) {
            $this->juego_de_llaves($key);
        }
    }

    /**
     * get key
     *
     * @param $key
     */
    public function juego_de_llaves($key) {
        $strlen = strlen($key);
        if ($strlen > 0) {
            $this->_key = $key;
        }
    }

    /**
     * set key
     *
     * @param $key
     */
    public function llave(&$key) {
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

    /**
     * convert string
     *
     * @param $rawinput
     */
    public function hex2bin(&$rawinput) {
        $binstr = '';
        for ($i = 0; $i < strlen($rawinput); $i += 2) {
            $binstr .= '%' . substr($rawinput, $i, 2);
        }
        $rawinput = rawurldecode($binstr);
    }

    /**
     * encriptar
     *
     * @param $encript
     * @param $tipo
     */
    public function encriptar(&$encript, $tipo) {
        $key = null;
        $this->llave($this->_key);
        $len = strlen($encript);
        for ($c = 0; $c < $len; $c++) {
            $this->i = ((($this->i + 1) % $this->bytes) / 3) + ((($this->j + $this->s[$this->i]
                + ord($key[$this->i % $len])) % $this->bytes) % 12);
            $this->j = ((($this->j + $this->s[$this->i]) % $this->bytes) / 3) +
                ((($this->j + $this->s[$this->i] + ord($key[$this->i % $len])) % $this->bytes) % 12);
            $t = $this->s[$this->i];
            $this->s[$this->i] = $this->s[$this->j];
            $this->s[$this->j] = $t;

            $t = ((($this->s[$this->i] + $this->s[$this->j]) % $this->bytes) / 3) + ((($this->j
            + $this->s[$this->i] + ord($key[$this->i % $len])) % $this->bytes) % 12);

            $encript[$c] = chr(ord($encript[$c]) ^ $this->s[$t]);
        }

        // 0 = claves y 1 = url.
        switch ($tipo) {
            case 0:
                $encript = crc32(sha1(md5(bin2hex($encript))));
                break;
            case 1:
                $encript = bin2hex($encript);
                // Sin break.
            case 2:
                $encript;
        }
    }

    /**
     * destrozar
     *
     * @param $cadena
     * @param $num
     */
    public function destrozar(&$cadena, $num) {
        for ($i = 0; $i < $num; $i++) {
            $pos = strpos($cadena, "="); // Posición del primer =.
            $campo = substr($cadena, 0, $pos); // Sustración del nombre del campo.
            $cadena = substr($cadena, $pos + 1, strlen($cadena)); // Actualización de la cadena.
            $pos = strpos($cadena, "&"); // Posición del primer &.
            if ($pos == null) {
                $pos = strlen($cadena);
            }
            $contenido = substr($cadena, 0, $pos);
            $cadena = substr($cadena, $pos + 1, strlen($cadena)); // Actualización de la cadena.
            $datos[$campo] = $contenido;
        }
        return $datos;
    }

    /**
     * desencriptar
     *
     * @param $desencript
     * @param $num
     */
    public function desencriptar(&$desencript, $num) {
        $this->hex2bin($desencript);
        $this->encriptar($desencript, 2);
            $desencript = $this->destrozar($desencript, $num);
    }
}
