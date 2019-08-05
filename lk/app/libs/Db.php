<?php 
/**
* WEB LK IMPULSE
* @author SAPSAN 隼
*
*/

namespace app\libs;

use PDO;

class Db{

	protected $db;
	protected $config;
	
	public function __construct() {
		$this->config = require 'app/configs/Config.php';
		$this->db = new PDO('mysql:host='.$this->config['host'].';dbname='.$this->config['name'].'', $this->config['user'], $this->config['pass']);
	}

	public function query($sql, $params = []) {
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $this->db->prepare($sql);
		if (!empty($params)) {
			foreach ($params as $key => $val) {
				if (is_int($val)) {
					$type = PDO::PARAM_INT;
				} else {
					$type = PDO::PARAM_STR;
				}
				$stmt->bindValue(':'.$key, $val, $type);
			}
		}
		$stmt->execute();
		return $stmt;
	}
	
	public function row($sql, $params = []) {
		$result = $this->query($sql, $params);
		return $result->fetchAll(PDO::FETCH_ASSOC);
	}

	public function one($sql, $params = []) {
		$result = $this->query($sql, $params);
		return $result->fetch(PDO::FETCH_COLUMN);
	}

	public function column($sql, $params = []) {
		$result = $this->query($sql, $params);
		return $result->fetchColumn();
	}

	public function lastInsertId() {
		return $this->db->lastInsertId();
	}

}