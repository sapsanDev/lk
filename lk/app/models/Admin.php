<?php

namespace app\models;

use app\models\DbModel;

class Admin extends DbModel{

	protected $name;

	public function satDonat(){
		if(empty($_SESSION['lk_admin']))exit;
		$allDonat = $this->db->one('SELECT SUM(all_cash) FROM lk');
		return number_format($allDonat,0,' ', ' ');
	}

	public function satDonatKass($system){
		if(empty($_SESSION['lk_admin']))exit;
		$params = ['name' => $system,'status' => 1,];
		$cashSYS = $this->db->one('SELECT SUM(pay_summ) FROM lk_pays WHERE pay_system = :name AND pay_status = :status', $params);
		if(empty($cashSYS)) return false;
		 return  number_format($cashSYS,0,' ', ' ');
	}

	public function satLogs(){
		if(empty($_SESSION['lk_admin']))exit;
		$alllogs = $this->db->row('SELECT * FROM lk_logs ORDER BY log_id DESC');
		return $alllogs;
	}

	public function satPromo(){
		if(empty($_SESSION['lk_admin']))exit;
		$allcodes = $this->db->row('SELECT * FROM lk_promocodes');
		return $allcodes;
	}

	public function contentLog($post){
		if(empty($_SESSION['lk_admin']))exit;
		if(empty($post['log_id']))exit;
		if(!preg_match('/^\d+$/i', $post['log_id']))
					$this->message('Ошибка в запросе', 'err');
		$param = ['log_id' => $post['log_id']];
		$contentLog = $this->db->one('SELECT log_content FROM lk_logs WHERE log_id = :log_id', $param);
		if(empty($contentLog))
			$this->message('Данного лога не существует', 'err');
		$this->message($contentLog, '');
	}


  /*****************************************************/
 /*изменение/добавление/проверка данных от промокодов */
/*****************************************************/	
	
	public function addPromo($post){
		if(empty($_SESSION['lk_admin']))exit;
		if(empty($post['promo']))
			$promo = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, rand(5,15));
		else $promo = $post['promo'];
		if(!preg_match('/^[A-z-0-9]{5,15}$/', $promo))
			$this->message('Промокод должен состоять и латинских символов и цифр!','Error');
		else if(empty($post['limit']))
			$this->message('Введите лимит использования промокода!','Error');
		else if(!preg_match('/^\d+$/', $post['limit']))
			$this->message('В поле лимит возможенн ввод только цифр!','Error');
		else if(empty($post['limit']))
			$this->message('Введите бонусный процент промокода!','Error');
		else if(!preg_match('/^[0-9\.]+$/', $post['bonuspecent']))
			$this->message('В поле бонусный процент возможенн ввод только целое значение либо с точкой 4.50!','Error');
		$param = ['code' => $promo];
		$expromo = $this->db->one('SELECT code FROM lk_promocodes WHERE code = :code', $param);
		if(!empty($expromo))
			$this->message('Такой промокод уже существует','Error');
		if(empty($post['auth']))
			$auth = 0;
		else{
			if(!preg_match('/^\d+$/', $post['auth']))
				$this->message('Ошибка Запроса!','Error');
			$auth = 1;
		}
		$params = [
			'promo'=>$promo,
			'attempts'=>$post['limit'],
			'bonuspecent'=>$post['bonuspecent'],
			'auth'=>$auth
		];
		$this->db->query('INSERT INTO lk_promocodes(code, percent, attempts, auth1) VALUES(:promo, :bonuspecent, :attempts, :auth)',$params);
		$this->message('Промокод "'.$promo.'" добавлен!','succ');
	}

	public function EditPromo($post){
		if(empty($_SESSION['lk_admin']))exit;
		if(empty($post['editid']))
			$this->message('Ошибка Запроса!','Error');
		else if(!preg_match('/^\d+$/',$post['editid']))
			$this->message('Ошибка Запроса!','Error');
		else if(empty($post['editpromo']))
			$this->message('Введитеназвание промокода!','Error');
		else if(!preg_match('/^[A-z-0-9]{5,15}$/', $post['editpromo']))
			$this->message('Промокод должен состоять и латинских символов и цифр!','Error');
		else if(empty($post['editlimit']))
			$this->message('Введите лимит использования промокода!','Error');
		else if(!preg_match('/^\d+$/', $post['editlimit']))
			$this->message('В поле лимит возможенн ввод только цифр!','Error');
		else if(empty($post['editlimit']))
			$this->message('Введите бонусный процент промокода!','Error');
		else if(!preg_match('/^[0-9\.]+$/', $post['editbonuspecent']))
			$this->message('В поле бонусный процент возможенн ввод только целое значение либо с точкой 4.50!','Error');
		else if(empty($post['editauth']))
			$auth = 0;
		else{
			if(!preg_match('/^\d+$/', $post['editauth']))
				$this->message('Ошибка Запроса!','Error');
			$auth = 1;
		}
		$param = ['id' => $post['editid']];
		$expromo = $this->db->one('SELECT id FROM lk_promocodes WHERE id = :id', $param);
		if(empty($expromo))
			$this->message('Ошибка Запроса!','Error');
		$params = [
			'id'=>$post['editid'],
			'promo'=>$post['editpromo'],
			'attempts'=>$post['editlimit'],
			'bonuspecent'=>$post['editbonuspecent'],
			'auth'=>$auth
		];
		$this->db->query('UPDATE lk_promocodes SET code=:promo, percent=:bonuspecent, attempts=:attempts, auth1=:auth WHERE id=:id ',$params);
		$this->message('Данные "'.$post['editpromo'].'" сохранены!','succ');
	}

	public function deletePromo($post){
		if(empty($_SESSION['lk_admin']))exit;
		if(empty($post['delete']))
			$this->message('Ошибка Запроса!','Error');
		else if(!preg_match('/^\d+$/',$post['delete']))
			$this->message('Ошибка Запроса!','Error');
		$param = ['id' => $post['delete']];
		$expromo = $this->db->one('SELECT id FROM lk_promocodes WHERE id = :id', $param);
		if(empty($expromo))
			$this->message('Ошибка Запроса!','Error');
		$this->db->query('DELETE FROM lk_promocodes WHERE id = :id', $param);
		$this->message('Промокод удален!','succ');
	}


  /***********************************************/
 /*изменение/добавление/проверка данных от касс */
/***********************************************/

	public function satKassa(){
		if(empty($_SESSION['lk_admin']))exit;
		$allKass = $this->db->row('SELECT * FROM lk_pay_service');
		return $allKass;
	}

	public function satKassaOn(){
		$allKass = $this->db->row('SELECT id, name_kassa FROM lk_pay_service WHERE status = 1');
		return $allKass;
	}

	public function addKassa($post){
			if(empty($_SESSION['lk_admin']))exit;
			if(!preg_match('/^\d+$/i', $post['kassa']))
					$this->message('Ошибка в запросе', 'err');
			else if($post['kassa'] > 10 or $post['kassa'] <= 0)
					$this->message('Ошибка в запросе', 'err');
			$this->validateKassa($post['kassa'],$post);
			$this->statusAddKass($post);
			$params = [
				'id' 		=> $post['kassa'],
				'name'		=> $this->name,
				'shopid'	=> $post['shopid'],
				'secret1'	=> $post['secret1'],
				'secret2'	=> $post['secret2'],
				'status'	=> 1,
			];
			$this->db->query('INSERT INTO lk_pay_service VALUES(:id, :name, :shopid, :secret1, :secret2, :status)',$params);
			$this->message('Система '.$this->name.' добавлена и включена', 'succ');
	}

	public function EditKassa($post){
			if(empty($_SESSION['lk_admin']))exit;
			if(!preg_match('/^\d+$/i', $post['editkassa']))
						$this->message('Ошибка в запросе', 'err');
			else if($post['editkassa'] > 10 or $post['editkassa'] <= 0)
						$this->message('Ошибка в запросе', 'err');
			if(isset($_POST['status']))
					$status = 1;
			else 	$status = 0;
			$params = [
				'id' 		=> $post['editkassa'],
				'shopid'	=> $post['editshopid'],
				'secret1'	=> $post['editsecret1'],
				'secret2'	=> $post['editsecret2'],
				'status'	=> $status,
			];
			$this->validateKassa($post['editkassa'],$params);
			$this->db->query('UPDATE lk_pay_service SET shop_id = :shopid, secret_key_1 = :secret1, secret_key_2 = :secret2, status = :status WHERE id = :id',$params);
			$this->message('Изменения данных в системе '.$this->name.' сохранены!', 'succ');
	}

	public function deleteKassa($post){
		if(empty($_SESSION['lk_admin']))exit;
		if(!preg_match('/^\d+$/i', $post['delete']))
				$this->message('Ошибка в запросе', 'err');
		$this->existsKassa($post['delete']);
		$param =['id'=> $post['delete']];
		$this->db->query('DELETE FROM lk_pay_service WHERE id = :id', $param);
		$this->message('Касса успешно удалена!','succ');
	}

	public function existsKassa($post){
			$param =['id'=> $post];
		$server = $this->db->one('SELECT id FROM lk_pay_service WHERE id = :id', $param);
		if(empty($server))$this->message('Такая касса не найдена!','err');
	}

	protected function statusAddKass($post){
			if(empty($_SESSION['lk_admin']))exit;
			$param =['id'=>$post['kassa']];
			$kassa = $this->db->one('SELECT id FROM lk_pay_service WHERE id = :id', $param);
			if($kassa)
			$this->message('Система '.$this->name.' уже добавлена','err');
	}


	protected function validateKassa($id,$post){
		switch ($id) {
			case 1:
				if(empty($post['shopid']))
					$this->message('Введите ID магазина в системе FreeKassa','err');
				else if(empty($post['secret1']))
					$this->message('Введите Секретное слово в системе FreeKassa','err');
				else if(empty($post['secret2']))
					$this->message('Введите Секретное слово 2 в системе FreeKassa','err');
				$this->name = 'FreeKassa';
			break;
			case 2:
				if(empty($post['shopid']))
					$this->message('Введите ID кассы в системе InterKassa','err');
				else if(empty($post['secret1']))
					$this->message('Введите Секретный ключ в системе InterKassa','err');
				else if($post['secret2'])
					$this->message('Секретный ключ 2 в системе InterKassa не требуется!','err');
				$this->name = 'InterKassa';
			break;
			case 3:
				if(empty($post['shopid']))
					$this->message('Введите ID кассы в системе RoboKassa','err');
				else if(empty($post['secret1']))
					$this->message('Введите Пароль #1 в системе RoboKassa','err');
				else if(empty($post['secret2']))
					$this->message('Введите Пароль #2 в системе RoboKassa','err');
				$this->name = 'RoboKassa';
			break;
			case 4:
				if($post['shopid'])
					$this->message('ID магазина в системе UnitPay не требуется!','err');
				else if(empty($post['secret1']))
					$this->message('Введите Публичный ключ системе UnitPay','err');
				else if(empty($post['secret2']))
					$this->message('Введите Секретный ключ в системе UnitPay','err');
				$this->name = 'UnitPay';
			break;
			case 6:
				if(empty($post['shopid']))
					$this->message('Введите номер кошелька в системе YandexMoney','err');
				else if(empty($post['secret1']))
					$this->message('Введите Секретное слово в системе YandexMoney','err');
				else if($post['secret2'])
					$this->message('Секретное слово 2 в системе YandexMoney не требуется!','err');
				$this->name = 'YandexMoney';
			break;
			case 7:
				if(empty($post['shopid']))
					$this->message('Введите номер кошелька в системе YandexMoney','err');
				else if(empty($post['secret1']))
					$this->message('Введите Секретное слово в системе YandexMoney','err');
				else if($post['secret2'])
					$this->message('Секретное слово 2 в системе YandexMoney не требуется!','err');
				$this->name = 'YandexMoney(карта)';
			break;
	
			case 8:
				if(empty($post['shopid']))
					$this->message('Введите номер кошелька в системе WebMoney','err');
				else if(empty($post['secret1']))
					$this->message('Введите Секретный ключ в системе WebMoney','err');
				else if($post['secret2'])
					$this->message('Секретный ключ 2 в системе WebMoney не требуется!','err');
				$this->name = 'WebMoney';
			break;
			case 9:
				if(empty($post['shopid']))
					$this->message('Введите E-mail бизнес аккаунта в системе PayPal ','err');
				else if(empty($post['secret1']))
					$this->message('В поле Секретный ключ 1 введите url оповещения Пример: http://mysite.ru/lk/paypal.php!','err');
				else if($post['secret2'])
					$this->message('Секретный ключ 2 в системе PayPal не требуется!','err');
				$this->name = 'PayPal';
			break;
			case 10:
				if($post['shopid'])
					$this->message('Идентификатор ID для Qiwi не требуется','err');
				else if(empty($post['secret1']))
					$this->message('В поле Секретный ключ 1 введите публичный ключ Qiwi!','err');
				else if(empty($post['secret2']))
					$this->message('В поле Секретный ключ 2 введите секретный ключ Qiwi!','err');
				$this->name = 'Qiwi';
			break;
			default:
				$this->message('Данная система еще не интегрированна','err');
				break;
		}
	}


	public function status($status){
		if(empty($status))$return = '<span class="m-icon mdi-fan norotate" style="font-size:30px;color: red;" data-toggle="tooltip" data-placement="left" title="" data-original-title="вне работы"></span> OFF';
		else $return = '<span class="m-icon mdi-fan rotate" style="font-size:30px;" data-toggle="tooltip" data-placement="left" title="" data-original-title="в работе"></span> ON';
		return $return;
	}

	public function DelUsers(){
		if(empty($_SESSION['lk_admin']))exit;
		$this->db->query('DELETE FROM lk WHERE !cash AND all_cash = 0');
		$this->message('Удалены все игроки с нулевым донатом','succ');
	}

	public function updateBalance($post){
		if(empty($_SESSION['lk_admin']))exit;
		if(!preg_match('/^[0-9]{1,5}.[0-9]{1,2}$/', $this->WM($post['balance'])))
				$this->message('Целое число 10 либо с точкой 10.84!','Error');
		$param = ['auth'=> $post['steam']];
		$infoUser = $this->db->row("SELECT * FROM lk WHERE auth = :auth", $param);
		if(empty($infoUser))$this->message('Такой игрок не найден', 'error');
		if(($post['balance']-$infoUser[0]['cash']) != 0){
			$params = [	'order'		=> time() % 100000,
						'auth'		=> $post['steam'],
						'summ'		=> $post['balance']-$infoUser[0]['cash'],
						'data'		=> date('d.m.Y в H:i:s'),
						'system'	=> 'admin',
						'promo'		=> '',
						'confirm'	=> 1,
					];
			$this->db->query('INSERT INTO lk_pays (pay_order, pay_auth, pay_summ, pay_data, pay_system, pay_promo, pay_status) VALUES(:order,:auth,:summ,:data,:system,:promo,:confirm)',$params);
		}
		$params = [
				'auth' 		=> $post['steam'],
				'cash'		=> $post['balance'],
			];
		$this->db->query('UPDATE lk SET cash = :cash WHERE auth = :auth',$params);
		$this->message('Новый баланс '.$infoUser[0]['name'].' сохранен!','succ');
	}

	public function cleanLogs(){
		if(empty($_SESSION['lk_admin']))exit;
		$this->db->query('DELETE FROM lk_logs WHERE log_id NOT IN (SELECT log_id FROM (SELECT log_id FROM lk_logs  ORDER BY log_id DESC LIMIT 30) x )');
		$this->message('Логи очищены!','succ');
	}

	protected function WM($summ){
		$ita = explode('.', $summ);
		if(COUNT($ita) == 1){
			$summa = $ita[0].'.00';
		}else{
			$summa = $summ;
		}
		return $summa;
	}
}