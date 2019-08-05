<?php 
/**
* LK Web 
* @author SAPSAN 隼
*
*/
require 'app/include/autoload.php';

if(empty($_GET['method']))die('Hacking....');
$Unitpay->payerUnit($_GET['method'],$_GET['params']);

