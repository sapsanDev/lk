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
$Top = $PlayerInfo->topPlayersList();
Top('ТОП ДОНАТЕРОВ', $balans);?>

<h3 class="text-center">Топ <?=COUNT($Top)?></h3>
<div class="panel bit-1">
	<?php if(empty($Top)):?>
<h4 class="text-center">Список пуст</h4>
	<?php else:?>
	<table class="table table-hover text-left" id="accordion">
	<thead class="thead-lite"><tr><th></th><th>STEAM ID</th><th>Сумма доната</th><th></th></tr></thead>
	<?php foreach($Top as $info) :?>
		<tr>
			<td>
				<a <?=$PlayerInfo->steam->Avatar($info['auth'])['Profileurl']?>>
				<img width="45" class="rounded-circle" src="<?=$PlayerInfo->steam->Avatar($info['auth'])['Avatar']?>"></a>
				&nbsp;<b><?=$PlayerInfo->steam->Avatar($info['auth'],$info['name'])['Name']?><b>
			</td>
			<td><?=$info['auth']?></td>
			<td><b><i class="m-icon mdi-currency-rub"></i> <?=number_format($info['all_cash'],0,' ', ' ')?></b></td>
		</tr>
	<?php endforeach?>
</table>

<?php endif;?>
</div>
<?=Bot()?>