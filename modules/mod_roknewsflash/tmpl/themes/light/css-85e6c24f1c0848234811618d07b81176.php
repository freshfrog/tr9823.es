<?php 
ob_start ("ob_gzhandler");
header("Content-type: text/css; charset= UTF-8");
header("Cache-Control: must-revalidate");
$expires_time = 1440;
$offset = 60 * $expires_time ;
$ExpStr = "Expires: " . 
gmdate("D, d M Y H:i:s",
time() + $offset) . " GMT";
header($ExpStr);
                ?>

/*** roknewsflash.css ***/

body div#newsflash.roknewsflash {border:0;margin:10px 0;position:relative;height:21px;} .roknewsflash .controls { width:55px;position:absolute;top:0;right:0;}.roknewsflash .controls span {display:none;}.roknewsflash .controls div {width:21px;height:21px;float:left;margin-left:4px;}.roknewsflash .controls .control-prev {background:url(images/arrows.png) 100% 0 no-repeat;}.roknewsflash .controls .control-next {background:url(images/arrows.png) 0 0 no-repeat;}.roknewsflash .controls .control-prev-hover {background:url(images/arrows.png) 100% -21px no-repeat;}.roknewsflash .controls .control-next-hover {background:url(images/arrows.png) 0 -21px no-repeat;}.roknewsflash .controls .control-prev-down {background:url(images/arrows.png) 100% -42px no-repeat;}.roknewsflash .controls .control-next-down {background:url(images/arrows.png) 0 -42px no-repeat;}.roknewsflash .flashing {position:absolute;top:0;left:0;line-height:21px;font-weight:bold;}.roknewsflash ul {margin:0;padding:0;margin-right:60px;}.roknewsflash li {list-style:none;margin:0;padding:0;line-height:21px;}