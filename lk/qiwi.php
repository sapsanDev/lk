<?php 
/**
* LK Web 
* @author SAPSAN 隼
*
*/
require 'app/include/autoload.php';

if(empty($_SERVER['HTTP_X_API_SIGNATURE_SHA256']))exit('Hacking...');

$POST_JSON = file_get_contents("php://input");

$POST_EN = json_decode($POST_JSON, true);

$Qiwi->getPay($POST_EN, $_SERVER['HTTP_X_API_SIGNATURE_SHA256']);