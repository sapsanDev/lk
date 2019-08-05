<?php 
/**
* LK Web 
* @author SAPSAN 隼
*
*/
require 'app/include/autoload.php';

if(empty($_POST))exit('Hacking...');
$Freekassa->CheckIP();
$Freekassa->CheckSignature($_POST);
$Freekassa->getPay($_POST);