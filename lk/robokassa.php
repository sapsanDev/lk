<?php 
/**
* LK Web 
* @author SAPSAN 隼
*
*/

require 'app/include/autoload.php';

if(empty($_POST))exit('Hacking...');

$Robokassa->CheckSignature($_POST);
$Robokassa->getPay($_POST);
