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
    aaaa bbbb cccc dddd aaaa bbbb cccc dddd aaaa bbbb cccc dddd aaaa bbbb cccc dddd aaaa bbbb cccc dddd aaaa bbbb cccc dddd aaaa bbbb cccc dddd
    </div>
</div>
  
</body>


</html>

