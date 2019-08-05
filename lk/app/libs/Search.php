<?php

namespace app\libs;

use app\models\DbModel;

class Search extends DbModel{
		public function SearchUser($post){
			$searchTrim = trim($post['search']);
			if(empty($searchTrim))$this->message('Введите SteamID или ник игрока', 'error');
			if(preg_match("/[\\\~^°!\"§$%\/()=?`';,\.{\[\]}\|<>@+#]/", $searchTrim))$this->message('Не Верный формат поиска', 'error');
			if (is_string($searchTrim)){
				$param = [
					'search'	=> "%$searchTrim%",
					'start' 	=> 0,
					'max'		=> 20,
				];
			$max = 20;
			$infoUser = $this->db->row("SELECT * FROM lk WHERE auth LIKE :search ORDER BY all_cash DESC LIMIT :start,:max", $param);
				if(empty($infoUser))
						$infoUser = $this->db->row("SELECT * FROM lk WHERE name LIKE :search ORDER BY all_cash  DESC LIMIT :start,:max", $param);
					if(!empty($infoUser)){
						foreach ($infoUser as $key) {
							$steamid[] = $this->steam->Steam_64($key['auth']);
							$cacheFile = "cache/".$this->steam->Steam_64($key['auth']).".txt";
						}
					$this->steam->CacheUsers($steamid,$cacheFile);
					$search[] = $infoUser;
					}
					else $search = false;
				}
    		$_SESSION['search'] = $search[0];
    		$this->location('search.php');
		}
}