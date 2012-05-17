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

/*** showcase.css ***/

.rg-sc {position: relative;}.rg-sc-main {position: relative;overflow:hidden;}.rg-sc-slice-container {position: absolute;top: 0;}.layout-left .rg-sc-slice-container {left: 0;}.layout-right .rg-sc-slice-container {right: 0;}.rg-sc-slice {position:absolute;top:0;left:0;display:none;}.rg-sc-img-list {overflow: hidden;position:relative;}.rg-sc-content {position: relative;}.rg-sc-info {position: absolute;top:0;left:0;}.layout-left .rg-sc-content {padding-left: 15px;}.layout-left .rg-sc-info {left: 15px;}.layout-right .rg-sc-content {padding-right: 15px;}.layout-right .rg-sc-info {right: 15px;}.rg-sc-title {font-size: 3em;line-height: 1em;margin: 15px 0 20px 0;}.rg-sc-title-span {display: block;}.rg-sc-caption {display: block;font-size: 1.4em;line-height: 1.3em;}.rg-sc-controls .prev, .rg-sc-controls .next {display: block;position: absolute;top: 50%;margin-top: -14px;width: 27px;height: 27px;border-radius: 27px;cursor: pointer;background-repeat: no-repeat;}.rg-sc-controls .prev {left: -35px;background-position: -15px 5px;}.rg-sc-controls .next {right: -35px;background-position: 10px 5px;}.rg-sc-controls .prev:hover {background-position: -15px -19px;}.rg-sc-controls .next:hover {background-position: 10px -19px;}.rg-sc-navigation {margin: 10px 0;}.rg-sc-thumbs-list, .rg-sc-pagination-list {margin: 0;padding: 0;list-style: none;}.rg-sc-thumbs-list li {display: inline-block;cursor: pointer;}.rg-sc-pagination-list li {display: inline-block;cursor: pointer;padding: 5px;margin: 0 2px;}