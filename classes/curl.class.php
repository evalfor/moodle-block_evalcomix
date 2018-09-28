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

defined('MOODLE_INTERNAL') || die();

class Curly {

    public $curl;
    public $manualfollow;
    public $redirecturl;
    public $cookiefile = null;
    public $headers = array();

    public function __construct() {
        $this->curl = curl_init();
        $this->headers[] = "Accept: */*";
        $this->headers[] = "Cache-Control: max-age=0";
        $this->headers[] = "Connection: keep-alive";
        $this->headers[] = "Keep-Alive: 300";
        $this->headers[] = "Accept-Charset: utf-8;ISO-8859-1;iso-8859-2;q=0.7,*;q=0.7";
        $this->headers[] = "Accept-Language: en-us,en;q=0.5";
        $this->headers[] = "Pragma: "; // Browsers keep this blank.

        curl_setopt($this->curl, CURLOPT_USERAGENT,
        'User-Agent:Mozilla/5.0 (Windows;U;Windows NT 6.0;en-GB;rv:1.9.0.14)Gecko/2009082707 Firefox/3.0.14 (.NET CLR 3.5.30729)');
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($this->curl, CURLOPT_VERBOSE, false);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($this->curl, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($this->curl, CURLOPT_AUTOREFERER, true);
        $this->set_ssl();

        if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) {
            curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
        } else {
            $this->manualfollow = true;
        }

        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_HEADER, false);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 0);

        $this->set_redirect();
    }

    public function add_header($header) {
        $this->headers[] = $header;
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);
    }

    public function header($val) {
        curl_setopt($this->curl, CURLOPT_HEADER, $val);
    }

    public function no_ajax() {
        foreach ($this->headers as $key => $val) {
            if ($val == "X-Requested-With: XMLHttpRequest") {
                unset($this->headers[$key]);
            }
        }
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);
    }

    public function set_ajax() {
        $this->headers[] = "X-Requested-With: XMLHttpRequest";
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);
    }

    public function set_ssl($username = null, $password = null) {
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($this->curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        if ($username && $password) {
            curl_setopt($this->curl, CURLOPT_USERPWD, "$username:$password");
        }
    }

    public function set_basic_auth($username, $password) {
        curl_setopt($this->curl, CURLOPT_HEADER, false);
        curl_setopt($this->curl, CURLOPT_USERPWD, "$username:$password");
    }


    public function set_cookie_file($file) {
        if (!file_exists($file)) {
            $handle = fopen($file, 'w+') or
                print('The cookie file could not be opened. Make sure this directory has the correct permissions');
            fclose($handle);
        }
        curl_setopt($this->curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($this->curl, CURLOPT_COOKIEJAR, $file);
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, $file);
        $this->cookiefile = $file;
    }

    public function get_cookies() {
        $contents = file_get_contents($this->cookiefile);
        $cookies = array();
        if ($contents) {
            $lines = explode("\n", $contents);
            if (count($lines)) {
                foreach ($lines as $key => $val) {
                    $tmp = explode("\t", $val);
                    if (count($tmp) > 3) {
                        $tmp[count($tmp) - 1] = str_replace("\n", "", $tmp[count($tmp) - 1]);
                        $tmp[count($tmp) - 1] = str_replace("\r", "", $tmp[count($tmp) - 1]);
                        $cookies[$tmp[count($tmp) - 2]] = $tmp[count($tmp) - 1];
                    }
                }
            }
        }
        return $cookies;
    }

    public function set_data_mode($val) {
         curl_setopt($this->curl, CURLOPT_BINARYTRANSFER, $val);
    }

    public function close() {
          curl_close($this->curl);
    }

    public function get_info() {
          return curl_getinfo($this->curl);
    }

    public function get_instance() {
        static $instance;
        if (!isset($instance)) {
            $curl = new Curl;
            $instance = array($curl);
        }
        return $instance[0];
    }

    public function set_timeout($connect, $transfer) {
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, $connect);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, $transfer);
    }

    public function get_error() {
        return curl_errno($this->curl) ? curl_error($this->curl) : false;
    }

    public function disable_redirect() {
        $this->set_redirect(false);
    }

    public function set_redirect($enable = true) {
        if ($enable) {
            $this->manualfollow = !curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
        } else {
            curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, false);
            $this->manualfollow = false;
        }
    }

    public function get_http_code() {
        return curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
    }


    public function make_query($data) {
        if (is_array($data)) {
            $fields = array();
            foreach ($data as $key => $value) {
                 $fields[] = $key . '=' . urlencode($value);
            }
            $fields = implode('&', $fields);
        } else {
            $fields = $data;
        }

        return $fields;
    }

    // FOLLOWLOCATION manually if we need to.
    public function maybefollow($page) {
        if (strpos($page, "\r\n\r\n") !== false) {
            list($headers, $page) = explode("\r\n\r\n", $page, 2);
        }

        $code = $this->get_http_code();

        if ($code > 300 && $code < 310) {
            $info = $this->get_info();

            preg_match("#Location: ?(.*)#i", $headers, $match);
            $this->redirecturl = trim($match[1]);

            if (substr_count($this->redirecturl, "http://") == 0 && isset($info['url']) && substr_count($info['url'], "http://")) {
                $urlparts = parse_url($info['url']);
                if (isset($urlparts['host']) && $urlparts['host']) {
                    $this->redirecturl = "http://".$urlparts['host'].$this->redirecturl;
                }
            }

            if ($this->manualfollow) {
                return $this->get($this->redirecturl);
            }
        } else {
            $this->redirecturl = '';
        }

        return $page;
    }


    public function plain_post($url, $data) {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);

        $page = curl_exec($this->curl);

        $error = curl_errno($this->curl);
        if ($error != CURLE_OK || empty($page)) {
            return false;
        }

        curl_setopt($this->curl, CURLOPT_POST, false);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, '');

        return $this->maybefollow($page);
    }

    public function post($url, $data) {
        $fields = $this->make_query($data);

        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $fields);
        $page = curl_exec($this->curl);

        $error = curl_errno($this->curl);
        if ($error != CURLE_OK || empty($page)) {
            return false;
        }

        curl_setopt($this->curl, CURLOPT_POST, false);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, '');

        return $this->maybefollow($page);
    }

    public function get($url, $data = null) {

        curl_setopt($this->curl, CURLOPT_FRESH_CONNECT, true);
        if (!is_null($data)) {
            $fields = $this->make_query($data);
            $url .= '?' . $fields;
        }

        curl_setopt($this->curl, CURLOPT_URL, $url);
        $page = curl_exec($this->curl);

        $error = curl_errno($this->curl);

        if ($error != CURLE_OK || empty($page)) {
            return false;
        }

        return $this->maybefollow($page);
    }
}
