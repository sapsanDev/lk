<?php

namespace app\models;

use app\libs\Db;
use app\libs\Steam;

class DbModel {
	
	public $db;
	public $steam;
	private $config;

	public function __construct() {
		$this->db = new Db;
		$this->steam = new Steam;
		$this->config = require 'app/configs/Config.php';
		if(!file_exists('cache/chek.txt')){
			$table_lk = $this->db->row("CHECK TABLE `lk`");
			$path = pathinfo(__DIR__);
			$prem = substr(sprintf('%o', fileperms(substr($path['dirname'], 0, -4))), -4);
			if($table_lk[0]['Msg_type'] === 'Error' || empty($this->config['steamKey']) || $prem !== '0777'){
				$title = 'Ошибки установки ЛК';
				require 'app/include/head.php';
					echo '<h3 class="text-center">Упс... Что-то пошло не так... O_o</h3><div class="panel bit-1">';

					if($table_lk[0]['Msg_text'] === "Table '".$this->config['name'].".lk' doesn't exist"){
					echo '<p>Не найдена таблица `lk` в базе данных '.$this->config['name'].'</p>
						<p>Возможно Вы не подключили плагин "Личный кабинет" к базе данных MySQL<br> для подключения введите данные в databases.cfg</p>
				<pre style="text-align:left">
				"lk"
				{
					"driver"   		"mysql" 
					"host"   		"'.$this->config['host'].'" 
					"database"  		"'.$this->config['name'].'" 
					"user"    		"'.$this->config['user'].'" 
					"pass"    		"'.$this->config['pass'].'"
				}</pre>';
					}
					if(empty($this->config['steamKey']))echo '<p>Создайте и укажите в Config.php <a href="https://steamcommunity.com/dev/apikey">Steam API Key</a> !</p>';
					if($prem !== '0777')echo '<p>Установите права <b>777</b> на папку где лежит ЛК !</p>';
				echo '</div>';
				require 'app/include/foot.php';
				exit;
			}else{
				if(!file_exists('cache'))mkdir("cache", 0777);
				$fp = fopen("cache/chek.txt", "w");
				fclose($fp);
			}
		}
	}

	public function message($text,$status){
				exit (trim(json_encode(array(
						'text' => $text,
						'status' => $status,
					))));
	}

	public function location($url){
				exit (trim(json_encode(array(
						'location' => $url,
					))));
	}
}