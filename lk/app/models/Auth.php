<?php 
/**
* WEB LK IMPULSE
* @author SAPSAN 隼
*
*/

namespace app\models;

use app\models\DbModel;
use app\libs\Openid;

class Auth extends DbModel{
	
	public function login(){
		if(isset($_SERVER['steam_id']))exit;
			$openid = new Openid($_SERVER["HTTP_HOST"]);
					if(!$openid->mode){
						$openid->identity = 'https://steamcommunity.com/openid';
						header('Location: ' . $openid->authUrl());
					}
					else{
						if($openid->validate()){ 
							$id = $openid->identity;
							$ptn = "/^https:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
							preg_match($ptn, $id, $matches);
							$_SESSION['steam_id'] = $this->steam->Steam_32($matches[1]);
							$_SESSION['name_player'] = $this->steam->PlayerInfo($matches[1])['personaname'];
							$_SESSION['avatar_player'] = $this->steam->PlayerInfo($matches[1])['avatarfull'];
							if (!headers_sent()){
								preg_match('/:[0-9]{1}:\d+/i', $_SESSION['steam_id'], $auth);
								$params = ['auth' => '%'.$auth[0].'%'];
								$player = $this->db->row('SELECT * FROM lk WHERE auth LIKE :auth', $params);
								if(empty($player)){
									$params = ['name' => $_SESSION['name_player'],'auth'=> $_SESSION['steam_id'],];
									$this->db->query('INSERT INTO lk VALUES (:auth, :name, 0, 0)', $params);
								}else if($player[0]['name'] != $_SESSION['name_player']){
									$params = ['name' => $_SESSION['name_player'],'auth'=> $_SESSION['steam_id'],];
									$this->db->query('UPDATE lk SET name = :name WHERE auth = :auth', $params);
								}
								header('Location:/lk');
							}
							else header('Location:/lk');
						}
						else header('Location:/lk');
					}
	}

	public function LoginAdmin($post){
		if(empty($post['login']))
				$this->message('Введите Логин','Error');
		else if(empty($post['pass']))
				$this->message('Введите Пароль','Error');
		$config = require 'app/configs/Config.php';
		if($post['login'] != $config['adminLogin'])
				$this->message('Не верный логин или пароль','Error');
		else if($post['pass'] != $config['adminPassw'])
				$this->message('Не верный логин или пароль','Error');
		$_SESSION['lk_admin'] = md5('Ipulse собака не сделал RCON нормальный!');
		$this->location('admin.php');

	}

	public function logout(){
		if(isset($_SESSION['steam_id'])){
			$url = $_SERVER['REQUEST_URI'];
        	$url = parse_url($url, PHP_URL_PATH);
			unset($_SESSION['steam_id']);
			header('Location:'.$url);
			return;
		}else return;
	}
	public function adminlogout(){
		if(isset($_SESSION['lk_admin'])){
			$url = $_SERVER['REQUEST_URI'];
        	$url = parse_url($url, PHP_URL_PATH);
			unset($_SESSION['lk_admin']);
			header('Location:'.$url);
			return;
		}else return;
	}
}
