<?php 
/**
* LK WEB
* @author SAPSAN 隼
*
*/
namespace app\libs\paysystems;

use app\models\DbModel;

class Basefunction extends DBmodel{

	public $kassa;
	public $decod;
	public $pay;
	public $summ;
	public $bonus;

	public function checkKassa($kassa){
		$param = ['id' => $this->decod[0]];
		$this->kassa = $this->db->row('SELECT * FROM lk_pay_service WHERE id = :id',$param);
		if(empty($this->kassa[0]['status'])){
			$this->addLog($kassa.' - Данная система выключена либо не используется');
				return false;
		}else return true;

	}

	public function checkPay($kassa){
		$params = [
			'order' 	=> $this->decod[1],
			'auth'		=> $this->decod[3],
			'status' 	=> 0,
		];
		$this->pay = $this->db->row('SELECT * FROM lk_pays WHERE pay_order = :order AND pay_auth = :auth AND pay_status = :status', $params);
		if(empty($this->pay)){
				$this->addLog($kassa.' - Платеж #'.$this->decod[1].' уже оплачен или не существует Steam: '.$this->decod[3].' Сумма: '.$this->decod[2]);
					return false;
		}else return true;
	}

	public function checkPlayer(){
		preg_match('/:[0-9]{1}:\d+/i', $this->decod[3], $auth);
		$param = ['auth' => '%'.$auth[0].'%'];
		$player = $this->db->one('SELECT auth FROM lk WHERE auth LIKE :auth ', $param);
		if(empty($player)){
			$auth = $this->steam->Steam_64($this->decod[3]);
			$auth2 = $this->steam->Steam_32($auth);
			$params = [
				'auth' 		=> $auth2,
				'name'		=> 'LK WEB BY SAPSAN',
				'cash'		=> 0,
				'all_cash'	=> 0,
			];
			$this->db->query('INSERT INTO lk(auth, name, cash, all_cash) VALUES (:auth,:name,:cash,:all_cash)',$params);
		}
	}

	public function checkPromo($kassa){
		$param = ['code' => $this->pay[0]['pay_promo']];
		$promoCode = $this->db->row('SELECT * FROM lk_promocodes WHERE code = :code', $param);
		if(empty($promoCode)){
			$this->summ = $this->decod[2];
		}
		else{
			$this->db->query('UPDATE lk_promocodes SET attempts = attempts - 1 WHERE code = :code',$param);
			$this->bonus = ($this->decod[2]/100)*$promoCode[0]['percent'];
			$this->summ = $this->bonus+$this->decod[2];
			$this->addLog($kassa.' - Указан промокод: '.$this->pay[0]['pay_promo'].' Бонус составил:'.$this->bonus.' руб.');
		}
	}

	public function updateBalance($steam,$summ){
		preg_match('/:[0-9]{1}:\d+/i', $steam, $auth);

		 $params = [
				'auth' 		=> '%'.$auth[0].'%',
				'cash'		=> $this->summ,
				'all_cash'	=> $summ,
			];
		$this->db->query('UPDATE lk SET cash = cash + :cash, all_cash = all_cash + :all_cash WHERE auth LIKE :auth',$params);
	}
	public function updatePay(){
		 $params = [
				'auth' 		=> $this->decod[3],
				'order'		=> $this->decod[1],
				'status'	=> 1,
			];
		$this->db->query('UPDATE lk_pays SET pay_status = :status WHERE pay_auth = :auth AND pay_order = :order', $params);
	}
	
	public function Decoder($string){
			$decod = base64_decode(base64_decode($string));
			return $decod;
	}

	public function addLog($act){
			date_default_timezone_set('Europe/Moscow');
			$param = [
				'log_name' => date('d_m_Y'),
			];
			$log = $this->db->row('SELECT * FROM lk_logs WHERE log_name = :log_name',$param);
			if(empty($log[0]['log_id'])){
				$params = [
				'log_name' 		=> date('d_m_Y'),
				'log_date'		=> date('d_m_Y H:i:s'),
				'log_content'	=> "LK WEB_".date('d_m_Y_H:i:s').": ".$act."\n",
			];
			$this->db->query('INSERT INTO lk_logs(log_name, log_date, log_content) VALUES (:log_name,:log_date,:log_content)',$params);
			}else{
				$params = [
				'log_id' 		=> $log[0]['log_id'],
				'log_content'	=> $log[0]['log_content']."LK WEB_".date('d_m_Y_H:i:s').": ".$act."\n",
			];
			$this->db->query('UPDATE lk_logs SET log_content = :log_content WHERE log_id = :log_id',$params);
			}
	}
}