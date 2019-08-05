<?php
include 'app/include/autoload.php';
if(empty($_SESSION['lk_admin'])){
	exit(header('Location: admin.php'));
}
if(isset($_POST['search']))
	$Search->SearchUser($_POST);
else if(isset($_POST['steam'])){
	$Admin->updateBalance($_POST);
	exit;
}
$i=1;
TopAdmin('LK WEB::Результат поиска');?>
<h3 class="text-center">Результат поиска</h3>
<div class="panel bit-1">
	<div class="material-input-group-text blue" style="width: 350px"><h6>Введите Ник или SteamID для поиска</h6>
	<form method="post"><input style="width: 250px" type="text" name="search"><button class="lts material-btn-cyan" type="submit">Поиск</button></form>
</div>
<?php if(empty($_SESSION['search'])):?>
	<h5>Поиск не дал результатов. Уточните Ваш запрос и повторите снова.</h5>
<?php else:?>
	<h5>Найдено результатов <?=COUNT($_SESSION['search'])?></h5>
<table class="table table-hover text-left" id="accordion">
<thead class="thead-lite"><tr><th>№</th><th></th><th>STEAM ID</th><th>Баланс</th><th>Всего задонел</th><th></th></tr></thead>
	<?php foreach($_SESSION['search'] as $info) :?>
		<tr>
			<td><?=$i++?></td>
			<td>
				<a <?=$PlayerInfo->steam->Avatar($info['auth'])['Profileurl']?>>
				<img width="32" class="rounded-circle" src="<?=$PlayerInfo->steam->Avatar($info['auth'])['Avatar']?>"></a>
				&nbsp;<b><?=$PlayerInfo->steam->Avatar($info['auth'],$info['name'])['Name']?><b>
			</td>
			<td><?=$info['auth']?></td>
			<td><i class="m-icon mdi-currency-rub"></i> <?=number_format($info['cash'],0,' ', ' ')?></td>
			<td><i class="m-icon mdi-currency-rub"></i> <?=number_format($info['all_cash'],0,' ', ' ')?></td>
			<td>
				<div class="material-btn-indigo" style="padding: 1rem;">
					<a id="heading<?=$PlayerInfo->steam->Steam_64($info['auth'])-173;?>" data-toggle="collapse" data-target="#collapse<?=$PlayerInfo->steam->Steam_64($info['auth'])-173;?>" aria-expanded="false" aria-controls="collapse<?=$PlayerInfo->steam->Steam_64($info['auth'])-173;?>">
					<i class="m-icon mdi-account-edit" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="редактировать"></i> 
				</a>
				</div>
				<div class="material-btn-indigo" style="padding: 1rem;">
					<a id="heading<?=$PlayerInfo->steam->Steam_64($info['auth'])?>" data-toggle="collapse" data-target="#collapse<?=$PlayerInfo->steam->Steam_64($info['auth'])?>" aria-expanded="false" aria-controls="collapse<?=$PlayerInfo->steam->Steam_64($info['auth'])?>">
					<i class="m-icon mdi-account-location" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="подробнее"></i> 
				</a>
				</div>
			</td>
		</tr>
		<tr><td style="padding: 0;" colspan="6">
			<div id="collapse<?=$PlayerInfo->steam->Steam_64($info['auth'])-173;?>" class="collapse" aria-labelledby="heading<?=$PlayerInfo->steam->Steam_64($info['auth'])-173;?>" data-parent="#accordion"><div class="bit-2" style="float: left">
				<form method="post">
					<div class="material-input-group-text blue">
							<div class="lableLogin"><small>Баланс <b><?=$PlayerInfo->steam->Avatar($info['auth'],$info['name'])['Name']?></b></small></div>
							<input type="hidden" name="steam" value="<?=$info['auth']?>">
							<input type="text" name="balance" placeholder class="m-input-blue" value="<?=$info['cash']?>">
							<span class="highlight blue"></span> 
							<span class="bar blue"></span>
							<span class="divider"></span>
							<button type="submit" class="lts material-btn-cyan">Сохранить</button>
					</div>
				</form>
			</div>
			</div>
			<div id="collapse<?=$PlayerInfo->steam->Steam_64($info['auth'])?>" class="collapse" aria-labelledby="heading<?=$PlayerInfo->steam->Steam_64($info['auth'])?>" data-parent="#accordion">
			<table class="table">
				<tr><th>№ID</th><th>#ПЛАТЕЖ</th><th>CИСТЕМА</th><th>ДАТА</th><th>СУММА</th><th>СТАТУС</th></tr>
				<?php foreach($PlayerInfo->PlayerPays($info['auth']) as $info):?>
					<tr><td><?=$info['pay_id']?></td>
						<td><?=$info['pay_order']?></td>
						<td><img src="pub/img/<?=$info['pay_system']?>.png"></td>
						<td><?=$info['pay_data']?></td>
						<td><i class="m-icon mdi-currency-rub"></i> <?=number_format($info['pay_summ'],0,' ', ' ')?></td>
						<td><?=$PlayerInfo->statusPay($info['pay_status'])?></td>
					</tr>
				<?php endforeach?>
			</table>
			</div>
		</td></tr>
	<?php endforeach?>
</table>
<?php if(COUNT($_SESSION['search'])>6):?>
<h5>Если не нешли что искали, то уточните Ваш запрос и повторите снова.</h5>
<?php endif;endif?>
</div>
<?=Bot()?>