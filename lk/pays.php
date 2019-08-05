<?php
include 'app/include/autoload.php';
if(empty($_SESSION['steam_id'])){
	exit(header('Location: /lk'));
}

if(isset($_GET['logout']))
	$Auth->logout();
$Pays = $PlayerInfo->PlayerPays($_SESSION['steam_id']);
top('LK WEB::Платежи', $balans);?>
<h3 class="text-center">Мои платежи</h3>
<div class="panel bit-1">
<?php if(empty($Pays)):?>
<h4 class="text-center">Список пуст</h4>
<?php else:?>
<table class="table table-hover text-left">
	<thead class="thead-lite"><tr><th>Платежная система</th><th>Дата создания</th><th>Сумма</th><th>Статус</th></tr></thead>
	<?php
	 foreach($Pays as $info):?>
			<tr>
				<td><img src="pub/img/<?=$info['pay_system']?>.png"></td>
				<td><?=$info['pay_data']?></td>
				<td><i class="m-icon mdi-currency-rub"></i> <?=number_format($info['pay_summ'],0,' ', ' ')?></td>
				<td><?=$PlayerInfo->statusPay($info['pay_status'])?></td>
			</tr>
	<?php endforeach?>
</table>
<?php endif;?>
</div>
<?=bot()?>