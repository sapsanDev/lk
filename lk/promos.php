<?php
include 'app/include/autoload.php';
if(empty($_SESSION['lk_admin'])){
	exit(header('Location: admin.php'));
}
if(isset($_POST['promo'])){
	$Admin->addPromo($_POST);
	exit;
}elseif(isset($_POST['editpromo'])){
	$Admin->EditPromo($_POST);
	exit;
}elseif(isset($_POST['delete'])){
	$Admin->deletePromo($_POST);
	exit;
}

$Promo = $Admin->satPromo();

TopAdmin('LK WEB::Промокоды');?>

<h3 class="text-center">Cписок промокодов</h3>
<div class="panel bit-1 text-left" id="accordion">
<?php if(empty($Promo)):?>
<h5 class="text-center">Нет промокодов</h5>
<?php else:?>
	<table class="table table-hover">
		<thead class="thead-lite"><th>Промокод</th><th>Лимит</th><th>Бонусный <i class="m-icon mdi-percent"></i></th><th>Привязка</th><th></th></thead>
		<?php foreach($Promo as $info):?>
			<tr>
				<td><strong><?=$info['code']?></strong></td>
				<td><?=$info['attempts']?></td>
				<td><i class="m-icon mdi-percent"></i> <?=$info['percent']?></td>
				<td><?php if(!empty($info['auth1'])):?>Включена<?php else:?>Выключена<?php endif?></td>
				<td>
					<div class="material-btn-indigo" style="padding: 1rem;float: left;margin-right: 5px;">
						<a id="heading<?=$info['code']?>" data-toggle="collapse" data-target="#collapse<?=$info['code']?>" aria-expanded="false" aria-controls="collapse<?=$info['code']?>">
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
					<div id="collapse<?=$info['code']?>" class="collapse" aria-labelledby="heading<?=$info['code']?>" data-parent="#accordion">
						<div class="bit-2" style="float: left">
							<form method="post">
								<input type="hidden" name="editid" value="<?=$info['id']?>">
							<div class="material-input-group-text blue">
								<div class="lableLogin">Изменить название</div>
								<input type="text" name="editpromo" placeholder class="m-input-blue" value="<?=$info['code']?>">
								<span class="highlight blue"></span> 
								<span class="bar blue"></span>
								<span class="divider"></span>
								<div class="lableLogin">Изменить лимит использования</div>
								<input type="text" name="editlimit" placeholder class="m-input-blue" value="<?=$info['attempts']?>">
								<span class="highlight blue"></span> 
								<span class="bar blue"></span>
								<span class="divider"></span>
								<div class="lableLogin">Изменить бонусный <i class="m-icon mdi-percent"></i></div>
								<input type="text" name="editbonuspecent" placeholder class="m-input-blue" value="<?=$info['percent']?>">
								<span class="highlight blue"></span> 
								<span class="bar blue"></span>
								<span class="divider"></span>
								<input type="checkbox" class="m-input-checkbox-lightblue" name="editauth" value="1"  id="ck_2" <?php if(!empty($info['auth1'])):?> data-label="Выключить 1 промо на 1 steamID" checked <?php else:?> data-label="Включить 1 промо на 1 steamID" <?php endif?>>
								<button type="submit" class="material-btn-lime">Сохранить изменения</button>
							</div>
						</form>
						</div>
					</div>
				</td>
			</tr>
		<?php endforeach?>
	</table>
<?php endif;?>
<a id="headingKassa" data-toggle="collapse" data-target="#collapseKassa" aria-expanded="false" aria-controls="collapseKassa">
		<div  class="material-btn-deeppurple" >Добавить промокод</div>
	</a>
	<div id="collapseKassa" class="collapse" aria-labelledby="headingKassa" data-parent="#accordion">
	<div class="panel bit-2" style="float: left">
		<form method="post">
		<div class="material-input-group-text blue">
			<div class="lableLogin">Название Промокода<br>
				<small style="font-size: 14px;">(пустое поле рандомное название)</small></div>
			<input type="text" name="promo" placeholder class="m-input-blue">
			<span class="highlight blue"></span> 
			<span class="bar blue"></span>
			<span class="divider"></span>
			<div class="lableLogin">Лимит использования</div>
			<input type="text" name="limit" placeholder class="m-input-blue">
			<span class="highlight blue"></span> 
			<span class="bar blue"></span>
			<span class="divider"></span>
			<div class="lableLogin">Бонусный <i class="m-icon mdi-percent"></i></div>
			<input type="text" name="bonuspecent" placeholder class="m-input-blue">
			<span class="highlight blue"></span> 
			<span class="bar blue"></span>
			<span class="divider"></span>
			<input type="checkbox" class="m-input-checkbox-lightblue" name="auth" value="1"  id="ck_2" data-label="Включить 1 промо на 1 steamID">
			<button type="submit" class="material-btn-lime">Добавить</button>
		</div>
	</form>
	</div>
	</div>
</div>
<?=bot()?>