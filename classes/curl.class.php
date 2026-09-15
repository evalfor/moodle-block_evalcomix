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
 * block_evalcomix_curl
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>,
 *             Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */

 /**
  * block_evalcomix_curl
  * @package    block_evalcomix
  * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
  * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
  * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>,
  *             Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
  */
class block_evalcomix_curl {
    /**
     * @var object
     */
    public $curl;
    /**
     * @var array
     */
    public $options;

    /**
     * Construct
     */
    public function __construct() {
        $this->curl = new curl();

        $headers = [];
        $headers[] = "Accept: */*";
        $headers[] = "Cache-Control: max-age=0";
        $headers[] = "Connection: keep-alive";
        $headers[] = "Keep-Alive: 300";
        $headers[] = "Accept-Charset: utf-8;ISO-8859-1;iso-8859-2;q=0.7,*;q=0.7";
        $headers[] = "Accept-Language: en-us,en;q=0.5";
        $headers[] = "Pragma: "; // Browsers keep this blank.
        $this->curl->setHeader($headers);

        $this->options = [];
        $this->options['CURLOPT_RETURNTRANSFER'] = true;
        $this->options['CURLOPT_SSL_VERIFYPEER'] = false;
        $this->options['CURLOPT_SSL_VERIFYHOST'] = false;
        $this->options['CURLOPT_HTTPAUTH'] = 'CURLAUTH_ANY';
        $this->options['CURLOPT_TIMEOUT'] = 0;
    }

    /**
     * get_info
     */
    public function get_info() {
          return $this->curl->get_info();
    }

    /**
     * get_error
     */
    public function get_error() {
        return $this->curl->get_errno();
    }

    /**
     * get_http_code
     */
    public function get_http_code() {
        $info = $this->get_info();
        if (!empty($info['http_code'])) {
            return $info['http_code'];
        } else {
            return 0;
        }
    }

    /**
     * post
     */
    public function post($url, $data) {
        return $this->curl->post($url, $data, $this->options);
    }

    /**
     * get
     */
    public function get($url, $data = null) {
        return $this->curl->get($url, $data, $this->options);
    }

    /**
     * delete
     */
    public function delete($url, $data = null) {
        return $this->curl->delete($url, $data, $this->options);
    }

    /**
     * put
     */
    public function put($url, $data = null) {
        return $this->curl->put($url, $data, $this->options);
    }
}
