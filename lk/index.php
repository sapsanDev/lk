<?php 
require 'app/include/autoload.php';
if(isset($_GET['login']))
	$Auth->login();
else if(isset($_GET['logout']))
	$Auth->logout();
if(isset($_POST['steam'])){
	$Ajax->pay($_POST);
	exit;
}elseif(!empty($_POST['promocode']) && !empty($_POST['summ']) && !empty($_POST['steamid'])){
	$Ajax->calcPromo($_POST['promocode'], $_POST['steamid'], $_POST['summ'] );
	exit;
}
Top('Личный Кабинет', $balans);?>
<h3 class="text-center">Пополнить баланс</h3>
<div class="panel bit-1">
	<div class="bit-2">
		<form method="post">
			<?php if(empty($KassaOn)):false;
		elseif(COUNT($KassaOn)>1):?>
		<div>Выберите платежную систему</div><br>
		<?php foreach($KassaOn as $info):?>
			<input type="radio" name="kassa" value="<?=$info['id']?>" id="kassa<?=$info['id']?>" class="paysystemKass">
			<label for="kassa<?=$info['id']?>" style="background: url('pub/img/<?=$info['name_kassa']?>.png') no-repeat;background-position: center;" class="paysystem"></label>
		<?php endforeach?>
		<span class="divider"></span>
		<?php else:?>
		<input type="hidden" name="kassa" value="<?=$KassaOn[0]['id']?>">
		<?php endif;?>
	<div class="material-input-group-text blue">
		<?php if(isset($_SESSION['steam_id'])):?>
			<input type="text" name="steam" placeholder class="m-input-blue" value="<?=$_SESSION['steam_id']?>" readonly>
		<?php  elseif(isset($_GET['steam'])):?>
			<input type="text" name="steam" placeholder class="m-input-blue" value="<?=$_GET['steam']?>" readonly>		
		<?php else:?>
		<div class="lableLogin">Steam ID (Пример: STEAM_1:0:123456789)</div>
		<input type="text" name="steam" placeholder class="m-input-blue">
		<span class="highlight blue"></span> 
		<span class="bar blue"></span>
	<?php endif;?>
	<span class="divider"></span>
	<div class="lableLogin">Сумма (минимальная 10 руб.)</div>
		<input type="text" name="summ" placeholder class="m-input-blue">
		<span class="highlight blue"></span> 
		<span class="bar blue"></span>
	<div class="lableLogin"><small>Промокод (если имеется)</small></div>
		<input type="text" name="promo" placeholder class="m-input-blue">
		<span class="highlight blue"></span> 
		<span class="bar blue"></span>
		<small id="promoresult" style="font-size: 16px;"></small>
	</div><br><br>
	<div><small>нажимая кнопку пополнить вы соглашаетесь с договором <a href="oferta.php">оферты</a></small></div>
	<br>
	<button class="lts material-btn-cyan">Пополнить</button>
</div>
</form>
</div>
<div id="result"></div>
<?=Bot()?>