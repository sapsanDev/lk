<?php
include 'app/include/autoload.php';
if(isset($_POST['login'])){
	$Auth->LoginAdmin($_POST);
	exit;
}
TopAdmin('LK WEB::Админ Панель');

if(empty($_SESSION['lk_admin'])):?>
		<h3 class="text-center">Панель Администратора</h3>
<div class="panel bit-1">
	<div class="bit-2">
		<form method="post">
		<div class="material-input-group-text blue">
			<div class="lableLogin">Логин</div>
			<input type="text" name="login" placeholder class="m-input-blue">
			<span class="highlight blue"></span> 
			<span class="bar blue"></span>
			<span class="divider"></span>
			<div class="lableLogin">Пароль</div>
			<input type="password" name="pass" placeholder class="m-input-blue">
			<span class="highlight blue"></span> 
			<span class="bar blue"></span>
			<button type="submit" class="lts material-btn-cyan">Вход</button>
	</div>
	</form>
	<div id="copy" class="footer-copyright py-3">© 2019 <a href="https://hlmod.ru/resources/lk-web-dlja-lk-ot-impulse.820/">LK WEB</a> Created by <a href="https://hlmod.ru/members/sapsan.83356/">SAPSAN</a></div>
</div>
</div>
<?php else:
	$Kassa = $Admin->satKassa();?>
				<h3 class="text-center">Статистика</h3>
	<div class="panel bit-1 text-left">
		<table class="table ">
			<thead><tr><th style="width: 300px">Системами</th><th>Всего собрано</th></tr></thead>
			<tr>
				<td>
					<div>
					<table class="table text-center">
						<thead><tr><th style="width: 110px">Cистема</th><th>Собрано</th></tr></thead>
						<?php foreach($Kassa as $Info):?>
							<tr><td><img src="pub/img/<?=$Info['name_kassa']?>.png"></td><td><i class="m-icon mdi-currency-rub"></i> <?=$Admin->satDonatKass($Info['name_kassa'])?></td></tr>
						<?php endforeach;?>
					</table>
					</div>
				</td>
				<td><i class="m-icon mdi-currency-rub"></i> <?=$Admin->satDonat()?></td>
				<td></td>
			</tr>
			
		</table>
	</div>
<?php endif;?>


<?=Bot()?>





