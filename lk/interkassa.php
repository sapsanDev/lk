<?php 
/**
* LK Web 
* @author SAPSAN 隼
*
*/
require 'app/include/autoload.php';

if(empty($_POST))exit('Hacking...');
$Interkassa->CheckSignature($_POST);
$Interkassa->getPay($_POST);