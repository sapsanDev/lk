<?php 
/**
* LK Web 
* @author SAPSAN 隼
*
*/

require 'app/include/autoload.php';

if(empty($_POST))exit('Hacking...');

if($Webmoney->CheckPurse($_POST));
else {
$Webmoney->CheckSignature($_POST);
$Webmoney->getPay($_POST);
}