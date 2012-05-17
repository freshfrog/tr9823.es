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

/*** rokcomments.css ***/

.rk-commentcount {margin-top:1em;}.rk-icon {background:url(comment.png) no-repeat;padding-left:21px;height:16px;line-height:16px;}