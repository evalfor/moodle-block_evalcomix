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

// Open a http channel, transmit data and return received buffer.
function xml_post($postxml, $url, $port) {
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    $ch = curl_init();    // Initialize curl handle.

    $headers[] = "Accept: */*";
    $headers[] = "Cache-Control: max-age=0";
    $headers[] = "Connection: keep-alive";
    $headers[] = "Keep-Alive: 300";
    $headers[] = "Accept-Charset: iso-8859-12;q=0.7,*;q=0.7";
    $headers[] = "Accept-Language: en-us,en;q=0.5";
    $headers[] = "Pragma: "; // Browsers keep this blank.

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_URL, $url); // Set url to post to.
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);              // Fail on errors.
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);    // Allow redirects.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Return into a variable.
    curl_setopt($ch, CURLOPT_PORT, $port);          // Set the port number.
    curl_setopt($ch, CURLOPT_TIMEOUT, 15); // Times out after 15s.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postxml); // Add POST fields.
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);

    if ($port == 443) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    }

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}