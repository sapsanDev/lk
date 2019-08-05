<?php
include 'app/include/autoload.php';
if(empty($_SESSION['lk_admin'])){
	exit(header('Location: admin.php'));
}
if(isset($_POST['kassa'])){
	$Admin->addKassa($_POST);
	exit;
}elseif(isset($_POST['editkassa'])){
	$Admin->EditKassa($_POST);
	exit;
}elseif(isset($_POST['delete'])){
	$Admin->deleteKassa($_POST);
	exit;
}

$Kassa 	= $Admin->satKassa();
TopAdmin('LK WEB::Добавить Кассу');?>
<h3 class="text-center">Cписок платежных систем</h3>
<div class="panel bit-1 text-left" id="accordion">
	<table class="table table-hover">
		<thead class="thead-lite"><th>Название</th><th>Состояние</th><th></th></thead>
		<?php foreach($Kassa as $info):?>
			<tr><td><img src="pub/img/<?=$info['name_kassa']?>.png"></td><td><?=$Admin->status($info['status'])?></td>
				<td>
					<div class="material-btn-indigo" style="padding: 1rem;float: left;margin-right: 5px;">
						<a id="heading<?=$info['id']?>" data-toggle="collapse" data-target="#collapse<?=$info['id']?>" aria-expanded="false" aria-controls="collapse<?=$info['id']?>">
						<i class="m-icon mdi-square-edit-outline" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="редактировать"></i> 
						</a>
					</div>
					<form method="post">
						<input type="hidden" name="delete" value="<?=$info['id']?>">
						<button class="material-btn-red" style="padding: 1rem;" data-toggle="tooltip" data-placement="right" title="" data-original-title="удалить">
							<i class="m-icon mdi-delete-forever"></i> 
						</button>
					</form>
				</td>
			</tr>
			<tr>
				<td style="padding: 0;" colspan="3">
					<div id="collapse<?=$info['id']?>" class="collapse" aria-labelledby="heading<?=$info['id']?>" data-parent="#accordion">
						<div class="bit-2" style="float: left">
							<form method="post">
							<div class="material-input-group-text blue">
								<input type="hidden" name="editkassa" value="<?=$info['id']?>">
								<div class="lableLogin">Изменить ID Магазина</div>
								<input type="text" name="editshopid" placeholder class="m-input-blue" value="<?=$info['shop_id']?>">
								<span class="highlight blue"></span> 
								<span class="bar blue"></span>
								<span class="divider"></span>
								<div class="lableLogin">Изменить Секретный ключ №1</div>
								<input type="text" name="editsecret1" placeholder class="m-input-blue" value="<?=$info['secret_key_1']?>">
								<span class="highlight blue"></span> 
								<span class="bar blue"></span>
								<span class="divider"></span>
								<div class="lableLogin">Изменить Секретный ключ №2</div>
								<input type="text" name="editsecret2" placeholder class="m-input-blue" value="<?=$info['secret_key_2']?>">
								<span class="highlight blue"></span> 
								<span class="bar blue"></span>
								<span class="divider"></span>
							</div>
							<input type="checkbox" class="m-input-checkbox-lightblue" name="status" value="1"  id="ck_2" <?php if($info['status']):?> data-label="Выключить" checked <?php else:?> data-label="Включить" <?php endif?>>
								<button type="submit" class="material-btn-lime">Сохранить данные</button>
						</form>
						</div>
					</div>
				</td>
			</tr>
		<?php endforeach?>
	</table>
<a id="headingKassa" data-toggle="collapse" data-target="#collapseKassa" aria-expanded="false" aria-controls="collapseKassa">
		<div  class="material-btn-deeppurple" >Добавить кассу</div>
	</a>
	<div id="collapseKassa" class="collapse" aria-labelledby="headingKassa" data-parent="#accordion">
	<div class="panel bit-2" style="float: left">
		<form method="post">
		<div class="material-input-group-text blue">
			<div>Выберите тип кассы</div>
			<select name="kassa" class="m-input-blue" style="font-size: 18px;">
				<option value="1">FreeKassa</option>
				<option value="2">InterKassa</option>
				<option value="3">RoboKassa</option>
				<option value="4">UnitPay</option>
				<option value="6">YandexMoney</option>
				<option value="7">YandexMoney (карта)</option>
				<option value="8">WebMoney</option>
				<option value="9">PayPal</option>
				<option value="10">Qiwi</option>
			</select>
			<span class="divider"></span>
			<div class="lableLogin">ID Магазина (Номер кошелька)</div>
			<input type="text" name="shopid" placeholder class="m-input-blue">
			<span class="highlight blue"></span> 
			<span class="bar blue"></span>
			<span class="divider"></span>
			<div class="lableLogin">Секретный ключ (слово/пароль) №1</div>
			<input type="text" name="secret1" placeholder class="m-input-blue">
			<span class="highlight blue"></span> 
			<span class="bar blue"></span>
			<span class="divider"></span>
			<div class="lableLogin">Секретный ключ (слово/пароль) №2</div>
			<input type="text" name="secret2" placeholder class="m-input-blue">
			<span class="highlight blue"></span> 
			<span class="bar blue"></span>
			<span class="divider"></span>
			<button type="submit" class="material-btn-lime">Добавить данные</button>
		</div>
	</form>
	</div>
	</div>
</div>
<?=bot()?>