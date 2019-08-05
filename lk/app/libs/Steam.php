<?php
/**
 * WEB LK INPULSE
 *
 * @author SAPSAN
 *
 */

namespace app\libs;

class Steam{

	protected $config;
	
	public function __construct() {
			$this->config = require 'app/configs/Config.php';
	}

	public function PlayerInfo($steamid){
					$c = curl_init("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=".$this->config['steamKey']."&steamids=".$steamid);
					curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
					$url = curl_exec($c);
					$content = json_decode($url, true);
					if(empty($content))return;
					foreach ($content as $respone) {
						foreach ($respone as $players) {
							foreach ($players as $key => $val) {
								$id = $val;
							}
						}
					} return $id;
	}

	public function CacheUsers($steamid,$cacheFile){
			if(COUNT($steamid) == 1){
						$steamid64 = $steamid[0];
					}else $steamid64 = implode(',',$steamid);
				if (file_exists($cacheFile) && (time() - 18000) > filemtime($cacheFile)){
						if(file_exists($cacheFile))unlink($cacheFile);
						$this->CreateCache($steamid64);
					}
				else if(!file_exists($cacheFile)){
						$this->CreateCache($steamid64);
					}
		}

	public function CreateCache($steamid){
		if(!file_exists('cache'))
				mkdir("cache", 0777);
		$c = curl_init("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=".$this->config['steamKey']."&steamids=".$steamid);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		$url = curl_exec($c);
		$content = json_decode($url, true);
		if(!empty($content)){
			foreach ($content as $respone) {
				foreach ($respone as $players) {
					foreach ($players as $key => $val) {
						$cacheFile = "cache/".$val['steamid'].".txt";
						$logFileHandle = fopen($cacheFile, 'a');
						fwrite($logFileHandle, $val['avatarmedium'].'|^@|'.$val['personaname'].'|^@|'.$val['profileurl']);
						fclose($logFileHandle);
					}
				}
			}
		}
	}

	public function Steam_32($steamid64){ 
        $pattern = "/^(7656119)([0-9]{10})$/"; 
        if (preg_match($pattern, $steamid64, $match)) { 
            $const1 = 7960265728;
            if($this->config['mode'] == 1)
            	$const2 = "STEAM_0:"; 
            elseif($this->config['mode'] == 2)
            	$const2 = "STEAM_1:";
            else die('Не верное значение мода игры!');
            $steam32 = 'STEAM_ID_STOP_IGNORING_RETVALS'; 
            if ($const1 <= $match[2]) { 
                $a = ($match[2] - $const1) % 2; 
                $b = ($match[2] - $const1 - $a) / 2; 
                $steam32 = $const2 . $a . ':' . $b; 
            } 
            return $steam32; 
        } 
        return false; 
    } 

    public function Steam_64($steamid){
				$steamid = substr($steamid, 8);
				$array = explode(":", $steamid);
				if(!empty($array[1])){
					$steamid_64 = (((int)$array[1] * 2) + (int)$array[0]) + 76561197960265728;
					return $steamid_64;
			}
	}

	public function Avatar($steamid,$name=""){
		$steam64 = $this->Steam_64($steamid);
		$cacheFile = "cache/".$steam64.".txt";
		if(file_exists($cacheFile)){
			$handle = fopen($cacheFile, "r");
			while (!feof($handle)) {
    			$buffer = fgets($handle, 4096);
			}
			fclose($handle);
			$info = explode('|^@|', $buffer);
			$return = [
				'Avatar'=>$info[0],
				'Name'=>$info[1],
				'Profileurl'=>'href="'.$info[2].'" target="_blank"',
			];
		}
		else{
			$return = [
				'Avatar' 	=>	'pub/img/noavatar.jpg',
				'Name'		=>	$name.' (No Steam)',
				'Profileurl'=>	false,
			];
		}
		return $return;
	}

}