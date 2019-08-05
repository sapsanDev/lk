<?php
/**
* LK WEB
* @author SAPSAN 隼
*
*/

include 'app/include/Dev.php';
use app\models\Auth;
use app\models\Ajax;
use app\models\Admin;
use app\libs\PlayerInfo;
use app\libs\Pagination;
use app\libs\Search;
use app\libs\paysystems\Freekassa;
use app\libs\paysystems\Interkassa;
use app\libs\paysystems\Yandexmoney;
use app\libs\paysystems\Robokassa;
use app\libs\paysystems\Webmoney;
use app\libs\paysystems\Paypal;
use app\libs\paysystems\Unitpay;
use app\libs\paysystems\Qiwi;

spl_autoload_register(function($class) {
    $path = str_replace('\\', '/', $class.'.php');
    if (file_exists($path)) {
        require $path;
    }
});

session_start();

if(empty($_GET['p']))$page = 1;
else $page = $_GET['p'];

$Auth 			= new Auth;
$Ajax 			= new Ajax;
$Admin 			= new Admin;
$Search 		= new Search;
$PlayerInfo 	= new PlayerInfo;
$Pagination 	= new Pagination($page,$PlayerInfo->countPlayers());
$Freekassa 		= new Freekassa;
$Interkassa 	= new Interkassa;
$Yandexmoney 	= new Yandexmoney;
$Webmoney 		= new Webmoney;
$Robokassa 		= new Robokassa;
$Paypal 		= new Paypal;
$Unitpay 		= new Unitpay;
$Qiwi 			= new Qiwi;
$KassaOn		= $Admin->satKassaOn();

if(isset($_GET['admlogout']))$Auth->adminlogout();

if(!empty($_SESSION['steam_id']))
	$balans = number_format($PlayerInfo->PlayerBalance($_SESSION['steam_id'])[0]['cash'],0,' ', ' ');
else $balans = false;

function Top($title,$balans=''){
	require 'app/include/head.php';
}

function Bot(){
	require 'app/include/foot.php';
}
function TopAdmin($title){
	require 'app/include/headAdmin.php';
}
