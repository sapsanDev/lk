<?php 
require 'app/include/autoload.php';
if(isset($_GET['login']))
	$Auth->login();
else if(isset($_GET['logout']))
	$Auth->logout();
if(isset($_POST['steam'])){
	$Ajax->pay($_POST);
	exit;
}
Top('Договор оферта', $balans);?>
<h3 class="text-center">Договор оферта</h3>
<div class="panel bit-1">
	Тут Ваш договор публичной оферты!
</div>
<?=Bot()?>