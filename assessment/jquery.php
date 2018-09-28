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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
  <title> - jsFiddle demo</title>
  
  <script type='text/javascript' src='http://code.jquery.com/jquery-1.4.2.js'></script>
  
  <link rel="stylesheet" type="text/css" href="/css/normalize.css"/>
  <link rel="stylesheet" type="text/css" href="/css/result-light.css"/>
  
  <style type='text/css'>
    .wrapper1, .wrapper2{width: 300px; border: none 0px RED;
overflow-x: scroll; overflow-y:hidden;}
.wrapper1{height: 20px; }
.wrapper2{height: 200px; }
.div1 {width:1000px; height: 20px; }
.div2 {width:1000px; height: 200px; background-color: #88FF88;
overflow: auto;}


  </style>
  


<script type='text/javascript'>//<![CDATA[ 
$(window).load(function(){
$(function(){
    $(".wrapper1").scroll(function(){
        $(".wrapper2")
            .scrollLeft($(".wrapper1").scrollLeft());
    });
    $(".wrapper2").scroll(function(){
        $(".wrapper1")
            .scrollLeft($(".wrapper2").scrollLeft());
    });
});
});//]]>  

</script>


</head>
<body>
  <div class="wrapper1">
    <div class="div1">
    </div>
</div>
<div class="wrapper2">
    <div class="div2">
    aaaa bbbb cccc dddd aaaa bbbb cccc dddd aaaa bbbb cccc dddd aaaa bbbb cccc dddd aaaa
    bbbb cccc dddd aaaa bbbb cccc dddd aaaa bbbb cccc dddd
    </div>
</div>
  
</body>


</html>

