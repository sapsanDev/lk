<?php
include 'app/include/autoload.php';
if(empty($_SESSION['lk_admin'])){
	exit(header('Location: admin.php'));
}
if(isset($_POST['log_id']))$Admin->contentLog($_POST);
else if(isset($_POST['cleanlog']))$Admin->cleanLogs();
TopAdmin('LK WEB::Просмотр логов');?>
<h3 class="text-center">Просмотр логов</h3>
<div class="panel bit-1">
	<div class="material-input-group-text blue" style="width: 350px">
		<button class="lts material-btn-cyan" onclick="CleanLog()">Очистить старые логи</button>
	</div>
<table class="table text-left">
	<thead class="thead-light"><tr><th class="headLog">Выбирете лог</th><th>Содержание лога</th></tr>
	<tr>
		<th class="logTitle">
			<select id="log" onchange="changeLog()">
				<option value="0"></option>
			<?php foreach($Admin->satLogs() as $info):?>
				<option value="<?=$info['log_id']?>"><?=$info['log_name']?></option>
			<?php endforeach?>	
			</select>
		</th>
		<th><pre><div id="contentLog"></div></pre></th>
	</tr>
</table>
</div>
<?=bot()?>