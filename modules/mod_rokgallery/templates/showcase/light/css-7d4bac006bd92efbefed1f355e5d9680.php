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

/*** style.css ***/

.rg-sc-slice-container {background: #ddd;}.rg-sc-controls .prev, .rg-sc-controls .next {background-color: #ddd;background-image: url(images/showcase-arrows.png);}.rg-sc-controls .prev:hover, .rg-sc-controls .next:hover {background-color: #eee;}.rg-sc-thumbs-list li {background: #000;}