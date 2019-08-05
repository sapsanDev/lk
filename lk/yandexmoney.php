<?php 
/**
* LK Web 
* @author SAPSAN 隼
*
*/

require 'app/include/autoload.php';

if(empty($_POST))exit('Hacking...');
$Yandexmoney->CheckSignature($_POST);
$Yandexmoney->getPay($_POST);