<?php

namespace app\libs;

use app\models\DbModel;

class PlayerInfo extends DbModel{
	
	public function PlayerBalance($steam){
		if(empty($steam))return false;
		preg_match('/:[0-9]{1}:\d+/i', $steam, $auth);
		$params = ['auth' => '%'.$auth[0].'%'];
		$return = $this->db->row('SELECT * FROM lk WHERE auth LIKE :auth', $params);
		return $return;
	}

	public function PlayersList($page){
		if(empty($_SESSION['lk_admin']))return false;
		if(empty($page))$page = 1;
		$max = 10;
		$start =(($page - 1) * $max);
		$params = [
			'start' => $start,
			'max'	=> $max,
		];
		$return = $this->db->row('SELECT * FROM lk ORDER BY all_cash DESC LIMIT :start,:max',$params);
		if(empty($return))return false;
		foreach ($return as $key) {
			$steamid[] = $this->steam->Steam_64($key['auth']);
			$cacheFile = "cache/".$this->steam->Steam_64($key['auth']).".txt";
		}
		$this->steam->CacheUsers($steamid,$cacheFile);
		return $return;
	}

	public function topPlayersList(){
		$params = [
			'start' => 0,
			'max'	=> 10,
		];
		$return = $this->db->row('SELECT * FROM lk WHERE all_cash != :start ORDER BY all_cash DESC LIMIT :start,:max',$params);
		if(empty($return))return false;
		foreach ($return as $key) {
			$steamid[] = $this->steam->Steam_64($key['auth']);
			$cacheFile = "cache/".$this->steam->Steam_64($key['auth']).".txt";
		}
		$this->steam->CacheUsers($steamid,$cacheFile);
		return $return;
	}

	public function countPlayers(){
			$column = $this->db->column('SELECT count(auth) FROM lk');
			return $column;
	}

	public function PlayerPays($steam){
		if(empty($steam))return false;
		preg_match('/:[0-9]{1}:\d+/i', $steam, $auth);
		$param = ['auth' => '%'.$auth[0].'%'];
		$return = $this->db->row('SELECT * FROM lk_pays WHERE pay_auth LIKE :auth ORDER BY pay_id DESC', $param);
		return $return;
	}

	public function statusPay($st){
		if(empty($st))$return = '<span class="m-icon mdi-close-circle" style="font-size:30px;color: red;" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="не оплачен"></span>';
		else $return = '<span class="m-icon mdi-check-circle" style="font-size:30px;color: green;" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="оплачен"></span>';
		return $return;;
	}
}